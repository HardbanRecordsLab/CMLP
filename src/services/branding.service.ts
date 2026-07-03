import { eq } from 'drizzle-orm';
import { db } from '../db/index.ts';
import { users, companies } from '../db/schema.ts';

export interface BrandingConfig {
  primaryColor: string;
  secondaryColor: string;
  logoUrl: string;
  fontFamily: string;
  playerSkin: 'light' | 'dark' | 'glass' | 'retro';
  welcomeMessage: string;
  outletName: string;
  customCSS: string;
}

const DEFAULT_BRANDING: BrandingConfig = {
  primaryColor: '#3b82f6',
  secondaryColor: '#1e293b',
  logoUrl: '',
  fontFamily: 'Inter, system-ui, sans-serif',
  playerSkin: 'dark',
  welcomeMessage: '',
  outletName: 'White Label Radio',
  customCSS: '',
};

export function mergeBrandingDefaults(config: Partial<BrandingConfig>): BrandingConfig {
  return { ...DEFAULT_BRANDING, ...config };
}

export async function getBrandingConfig(companyId: number): Promise<BrandingConfig> {
  const rows = await db.select().from(companies).where(eq(companies.id, companyId));
  if (rows.length === 0) return { ...DEFAULT_BRANDING };

  const c = rows[0];
  return mergeBrandingDefaults({
    outletName: c.name || undefined,
  });
}

export async function getBrandingConfigByOutlet(outletUid: string): Promise<BrandingConfig> {
  const rows = await db.select().from(users).where(eq(users.uid, outletUid));
  if (rows.length === 0) return { ...DEFAULT_BRANDING };

  const u = rows[0];
  return mergeBrandingDefaults({
    primaryColor: u.primaryColor || undefined,
    secondaryColor: u.secondaryColor || undefined,
    logoUrl: u.logoUrl || undefined,
    fontFamily: u.fontFamily || undefined,
    playerSkin: (u.playerSkin as BrandingConfig['playerSkin']) || undefined,
    welcomeMessage: u.welcomeMessage || undefined,
    outletName: u.outletName || u.appName || undefined,
    customCSS: u.customCSS || undefined,
  });
}
