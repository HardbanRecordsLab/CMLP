import { Request, Response } from 'express';
import bcrypt from 'bcryptjs';
import { eq, desc, isNotNull } from 'drizzle-orm';
import { db } from '../db/index.ts';
import { users, companies, locations, usage_logs } from '../db/schema.ts';
import { signToken, signRefreshToken } from '../lib/jwt.ts';
import { logAuditEvent } from '../services/logging.service.ts';
import { getBrandingConfigByOutlet } from '../services/branding.service.ts';

export async function login(req: Request, res: Response) {
  try {
    const { pin } = req.body;
    if (!pin) { res.status(400).json({ error: 'PIN required' }); return; }

    const allUsers = await db.select().from(users).where(isNotNull(users.pin));
    let user = null as typeof allUsers[0] | null;

    for (const candidate of allUsers) {
      if (!candidate.pin) continue;
      const pinMatch =
        candidate.pin === pin ||
        (candidate.pin.startsWith('$2') && await bcrypt.compare(pin, candidate.pin));
      if (pinMatch) {
        user = candidate;
        break;
      }
    }

    if (!user) {
      res.status(401).json({ error: 'Invalid PIN' }); return;
    }
    await logAuditEvent({
      userId: String(user.id),
      action: 'user_login',
      resource: 'users',
      details: `Outlet credentials login for ${user.email} from PIN`,
      ipAddress: req.ip,
    });

    const accessToken = signToken({
      uid: user.uid,
      email: user.email,
      role: user.role,
      type: 'outlet',
    });

    const refreshToken = signRefreshToken({
      uid: user.uid,
      email: user.email,
      role: user.role,
      type: 'outlet',
    });

    const branding = await getBrandingConfigByOutlet(user.uid);

    res.json({
      accessToken,
      refreshToken,
      config: {
        appName: branding.outletName,
        primaryColor: branding.primaryColor,
        secondaryColor: branding.secondaryColor,
        logoUrl: branding.logoUrl,
        fontFamily: branding.fontFamily,
        playerSkin: branding.playerSkin,
        welcomeMessage: branding.welcomeMessage,
        outletName: branding.outletName,
        customCSS: branding.customCSS,
      },
    });
  } catch (e) {
    console.error(e);
    res.status(500).json({ error: 'Login error' });
  }
}

export async function getLocations(req: any, res: Response) {
  try {
    const userUid = req.user?.uid;
    if (!userUid) { res.status(401).json({ error: 'Unauthorized' }); return; }

    const userCompanies = await db.select().from(companies).where(eq(companies.ownerId, userUid));
    if (userCompanies.length === 0) {
      res.status(404).json({ error: 'No company found for user' }); return;
    }
    const company = userCompanies[0];

    const allLocations = await db.select().from(locations).where(eq(locations.companyId, company.id));

    const enriched = await Promise.all(allLocations.map(async (loc) => {
      const lastUsage = await db.select({ playedAt: usage_logs.playedAt })
        .from(usage_logs)
        .where(eq(usage_logs.companyName, company.name))
        .orderBy(desc(usage_logs.playedAt))
        .limit(1);

      const lastPlaybackTime = lastUsage.length > 0 ? lastUsage[0].playedAt : null;

      let status = 'unknown';
      if (loc.complianceStatus && typeof loc.complianceStatus === 'object') {
        const cs = loc.complianceStatus as Record<string, any>;
        if (cs.status) status = cs.status;
      }
      if (status === 'unknown' && lastPlaybackTime) {
        const hoursAgo = (Date.now() - new Date(lastPlaybackTime).getTime()) / (1000 * 60 * 60);
        status = hoursAgo < 1 ? 'online' : 'offline';
      }

      return { ...loc, status, lastPlaybackTime };
    }));

    res.json(enriched);
  } catch (e) {
    console.error(e);
    res.status(500).json({ error: 'Failed to load locations' });
  }
}

export async function updateLocation(req: any, res: Response) {
  try {
    const id = parseInt(req.params.id, 10);
    const { playlistAssignment, schedule } = req.body;

    const [location] = await db.select().from(locations).where(eq(locations.id, id));
    if (!location) { res.status(404).json({ error: 'Location not found' }); return; }

    if (req.user?.role !== 'admin') {
      const userCompanies = await db.select().from(companies).where(eq(companies.ownerId, req.user.uid));
      const hasAccess = userCompanies.some(c => c.id === location.companyId);
      if (!hasAccess) {
        return res.status(403).json({ error: 'Forbidden: insufficient permissions' });
      }
    }

    const updateData: Record<string, any> = {};

    if (playlistAssignment !== undefined) {
      updateData.playlists = playlistAssignment;
    }

    if (schedule !== undefined) {
      const existing = await db.select({ complianceStatus: locations.complianceStatus })
        .from(locations).where(eq(locations.id, id));
      const current = existing.length > 0 ? (existing[0].complianceStatus as Record<string, any> || {}) : {};
      updateData.complianceStatus = { ...current, schedule };
    }

    if (Object.keys(updateData).length === 0) {
      res.status(400).json({ error: 'No fields to update' }); return;
    }

    const [updated] = await db.update(locations)
      .set(updateData)
      .where(eq(locations.id, id))
      .returning();

    if (!updated) { res.status(404).json({ error: 'Location not found' }); return; }

    await logAuditEvent({
      userId: req.user?.uid || 'system',
      action: 'location_update',
      resource: 'locations',
      details: `Updated location ${updated.name} (ID: ${id})`,
      ipAddress: req.ip,
    });

    res.json(updated);
  } catch (e) {
    console.error(e);
    res.status(500).json({ error: 'Failed to update location' });
  }
}

export async function bulkUpdateLocations(req: any, res: Response) {
  try {
    const { ids, playlistAssignment, schedule } = req.body;
    if (!ids || !Array.isArray(ids) || ids.length === 0) {
      res.status(400).json({ error: 'Array of location IDs required' }); return;
    }

    if (req.user?.role !== 'admin') {
      const userCompanies = await db.select().from(companies).where(eq(companies.ownerId, req.user.uid));
      const allLocations = await db.select().from(locations);
      for (const locId of ids) {
        const loc = allLocations.find(l => l.id === locId);
        if (!loc || !userCompanies.some(c => c.id === loc.companyId)) {
          return res.status(403).json({ error: 'Forbidden: insufficient permissions for one or more locations' });
        }
      }
    }

    if (schedule !== undefined) {
      for (const locId of ids) {
        const existing = await db.select({ complianceStatus: locations.complianceStatus })
          .from(locations).where(eq(locations.id, locId));
        const current = existing.length > 0 ? (existing[0].complianceStatus as Record<string, any> || {}) : {};
        await db.update(locations)
          .set({ complianceStatus: { ...current, schedule } })
          .where(eq(locations.id, locId));
      }
    }

    const updateData: Record<string, any> = {};
    if (playlistAssignment !== undefined) updateData.playlists = playlistAssignment;

    if (Object.keys(updateData).length > 0) {
      for (const locId of ids) {
        await db.update(locations).set(updateData).where(eq(locations.id, locId));
      }
    }

    await logAuditEvent({
      userId: req.user?.uid || 'system',
      action: 'bulk_location_update',
      resource: 'locations',
      details: `Bulk updated ${ids.length} locations`,
      ipAddress: req.ip,
    });

    res.json({ message: `Updated ${ids.length} locations`, updatedIds: ids });
  } catch (e) {
    console.error(e);
    res.status(500).json({ error: 'Failed to bulk update locations' });
  }
}
