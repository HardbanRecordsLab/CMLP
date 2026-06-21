// @ts-ignore
import { pgTable } from 'drizzle-orm/pg-core';
// @ts-ignore
import { relations } from 'drizzle-orm';
// @ts-ignore
import { integer, serial, text, timestamp, boolean, jsonb } from 'drizzle-orm/pg-core/columns';
import { index } from 'drizzle-orm/pg-core/indexes';

export const users = pgTable('users', {
  id: serial('id').primaryKey(),
  uid: text('uid').notNull().unique(),
  email: text('email').notNull(),
  name: text('name'),
  role: text('role').default('subscriber').notNull(),
  pmproLevel: integer('pmpro_level').default(1),
  pin: text('pin'),
  logoUrl: text('logo_url'),
  primaryColor: text('primary_color').default('#3b82f6'),
  appName: text('app_name').default('Background Music Player'),
  mfaEnabled: boolean('mfa_enabled').default(false).notNull(),
  mfaSecret: text('mfa_secret'),
  createdAt: timestamp('created_at').defaultNow(),
});

export const companies = pgTable('companies', {
  id: serial('id').primaryKey(),
  name: text('name').notNull(),
  createdAt: timestamp('created_at').defaultNow(),
});

export const tracks = pgTable('tracks', {
  id: serial('id').primaryKey(),
  title: text('title').notNull(),
  artist: text('artist').notNull(),
  album: text('album'),
  year: integer('year'),
  bpm: integer('bpm'),
  genre: text('genre'),
  mood: jsonb('mood'),
  durationMs: integer('duration_ms'),
  explicit: boolean('explicit').default(false),
  timeOfDay: jsonb('time_of_day'),
  isrc: text('isrc'),
  coverUrl: text('cover_url'),
  filename: text('filename').notNull(),
  createdAt: timestamp('created_at').defaultNow(),
}, (table) => ({
  titleIdx: index('tracks_title_idx').on(table.title),
  artistIdx: index('tracks_artist_idx').on(table.artist),
  genreIdx: index('tracks_genre_idx').on(table.genre),
}));

export const playlists = pgTable('playlists', {
  id: serial('id').primaryKey(),
  title: text('title').notNull(),
  description: text('description'),
  authorUid: text('author_uid').notNull(),
  isPublic: boolean('is_public').default(false).notNull(),
  createdAt: timestamp('created_at').defaultNow(),
}, (table) => ({
  titleIdx: index('playlists_title_idx').on(table.title),
}));

export const playlist_tracks = pgTable('playlist_tracks', {
  id: serial('id').primaryKey(),
  playlistId: integer('playlist_id').references(() => playlists.id, { onDelete: 'cascade' }),
  trackId: integer('track_id').references(() => tracks.id, { onDelete: 'cascade' }),
  sequence: integer('sequence').default(0),
});

export const licenses = pgTable('licenses', {
  id: serial('id').primaryKey(),
  companyName: text('company_name').notNull(),
  licenseType: text('license_type').notNull(),
  status: text('status').default('active').notNull(),
  certificateNumber: text('certificate_number').notNull().unique(),
  issuedAt: timestamp('issued_at').defaultNow().notNull(),
  expiresAt: timestamp('expires_at').notNull(),
  authorUid: text('author_uid').notNull(),
  jurisdiction: text('jurisdiction').default('EU').notNull(),
  createdAt: timestamp('created_at').defaultNow()
});

export const contracts = pgTable('contracts', {
  id: serial('id').primaryKey(),
  licenseId: integer('license_id').references(() => licenses.id, { onDelete: 'cascade' }),
  contractText: text('contract_text').notNull(),
  signed: boolean('signed').default(false).notNull(),
  signedAt: timestamp('signed_at'),
  createdAt: timestamp('created_at').defaultNow()
});

export const invoices = pgTable('invoices', {
  id: serial('id').primaryKey(),
  userId: integer('user_id').references(() => users.id, { onDelete: 'set null' }),
  amount: integer('amount').notNull(),
  status: text('status').notNull(),
  createdAt: timestamp('created_at').defaultNow(),
});

export const payments = pgTable('payments', {
  id: serial('id').primaryKey(),
  userId: integer('user_id').references(() => users.id, { onDelete: 'set null' }),
  amount: integer('amount').notNull(),
  currency: text('currency').default('PLN').notNull(),
  gateway: text('gateway').notNull(),
  transactionType: text('transaction_type').notNull(),
  status: text('status').default('pending').notNull(),
  gatewayTransactionId: text('gateway_transaction_id'),
  licenseId: integer('license_id').references(() => licenses.id, { onDelete: 'set null' }),
  createdAt: timestamp('created_at').defaultNow()
}, (table) => ({
  gatewayTransactionIdx: index('payments_gateway_transaction_idx').on(table.gatewayTransactionId),
}));

export const wordpress_settings = pgTable('wordpress_settings', {
  id: serial('id').primaryKey(),
  wpUrl: text('wp_url').default('https://example.com/wp-json').notNull(),
  appUsername: text('app_username').default('admin').notNull(),
  appPassword: text('app_password').default('').notNull(),
  bidirectional: boolean('bidirectional').default(true).notNull(),
  lastSyncTime: timestamp('last_sync_time')
});

export const wordpress_sync_logs = pgTable('wordpress_sync_logs', {
  id: serial('id').primaryKey(),
  wpId: integer('wp_id'),
  wpType: text('wp_type').notNull(),
  title: text('title').notNull(),
  status: text('status').default('synced').notNull(),
  direction: text('direction').notNull(),
  errorMessage: text('error_message'),
  syncTime: timestamp('sync_time').defaultNow().notNull()
});

export const notification_settings = pgTable('notification_settings', {
  id: serial('id').primaryKey(),
  provider: text('provider').default('smtp').notNull(),
  smtpHost: text('smtp_host').default('smtp.mailtrap.io').notNull(),
  smtpPort: integer('smtp_port').default(587).notNull(),
  smtpUser: text('smtp_user').default('').notNull(),
  smtpPass: text('smtp_pass').default('').notNull(),
  sendgridApiKey: text('sendgrid_api_key').default('').notNull(),
  fromEmail: text('from_email').default('noreply@hrl.pl').notNull(),
  fromName: text('from_name').default('Hardban Records Lab').notNull(),
  templateWelcomeSubject: text('template_welcome_subject').default('Welcome to Hardban Records Lab!').notNull(),
  templateWelcomeBody: text('template_welcome_body').default('Hello, welcome to Hardban Records Lab!').notNull(),
  templateExpirySubject: text('template_expiry_subject').default('License Expiring Soon').notNull(),
  templateExpiryBody: text('template_expiry_body').default('Your license is expiring soon.').notNull(),
  templatePaymentSubject: text('template_payment_subject').default('Payment Receipt').notNull(),
  templatePaymentBody: text('template_payment_body').default('Thank you for your payment.').notNull()
});

export const notification_logs = pgTable('notification_logs', {
  id: serial('id').primaryKey(),
  channel: text('channel').notNull(),
  recipient: text('recipient').notNull(),
  notificationType: text('notification_type').notNull(),
  subject: text('subject').notNull(),
  body: text('body').notNull(),
  status: text('status').default('sent').notNull(),
  errorMessage: text('error_message'),
  createdAt: timestamp('created_at').defaultNow().notNull()
});

export const audit_logs = pgTable('audit_logs', {
  id: serial('id').primaryKey(),
  userId: text('user_id'),
  action: text('action').notNull(),
  resource: text('resource').notNull(),
  details: text('details').notNull(),
  ipAddress: text('ip_address'),
  createdAt: timestamp('created_at').defaultNow().notNull()
});

export const usage_logs = pgTable('usage_logs', {
  id: serial('id').primaryKey(),
  companyName: text('company_name'),
  trackId: integer('track_id').references(() => tracks.id, { onDelete: 'set null' }),
  trackTitle: text('track_title').notNull(),
  outletIp: text('outlet_ip'),
  durationPlayedSecond: integer('duration_played_second'),
  playedAt: timestamp('played_at').defaultNow().notNull()
});

export const vod_content = pgTable('vod_content', {
  id: serial('id').primaryKey(),
  title: text('title').notNull(),
  description: text('description'),
  filename: text('filename').notNull(),
  thumbnailUrl: text('thumbnail_url'),
  durationMs: integer('duration_ms'),
  mimeType: text('mime_type').default('video/mp4').notNull(),
  isPublic: boolean('is_public').default(false).notNull(),
  authorUid: text('author_uid').notNull(),
  createdAt: timestamp('created_at').defaultNow()
});

export const usersRelations = relations(users, ({ many }) => ({
  invoices: many(invoices),
  payments: many(payments),
}));

export const playlistsRelations = relations(playlists, ({ many }) => ({
  playlistTracks: many(playlist_tracks),
}));

export const tracksRelations = relations(tracks, ({ many }) => ({
  playlistTracks: many(playlist_tracks),
  usageLogs: many(usage_logs),
}));

export const playlistTracksRelations = relations(playlist_tracks, ({ one }) => ({
  playlist: one(playlists, {
    fields: [playlist_tracks.playlistId],
    references: [playlists.id],
  }),
  track: one(tracks, {
    fields: [playlist_tracks.trackId],
    references: [tracks.id],
  }),
}));

export const licensesRelations = relations(licenses, ({ many }) => ({
  contracts: many(contracts),
  payments: many(payments),
}));

export const contractsRelations = relations(contracts, ({ one }) => ({
  license: one(licenses, {
    fields: [contracts.licenseId],
    references: [licenses.id],
  }),
}));

export const invoicesRelations = relations(invoices, ({ one }) => ({
  user: one(users, {
    fields: [invoices.userId],
    references: [users.id],
  }),
}));

export const paymentsRelations = relations(payments, ({ one }) => ({
  user: one(users, {
    fields: [payments.userId],
    references: [users.id],
  }),
  license: one(licenses, {
    fields: [payments.licenseId],
    references: [licenses.id],
  }),
}));

export const usageLogsRelations = relations(usage_logs, ({ one }) => ({
  track: one(tracks, {
    fields: [usage_logs.trackId],
    references: [tracks.id],
  }),
}));

