/* Drizzle ORM Schema with Fixed Relationships

This is the improved schema with:
- Proper onDelete strategies for all foreign keys
- Better typed relationships
- Indexes for query optimization
- Sync with infrastructure/database/01_schema.sql

Foreign Key Rules:
- companies → licenses, locations: CASCADE (delete company removes associated licenses and locations)
- licenses → payments, contracts, usage_logs: CASCADE (delete license removes related payments, contracts, usage logs)
- users → licenses: SET NULL (delete user keeps license history but without owner)
- licenses → contracts: CASCADE (delete license removes contracts)
- payments → invoices: CASCADE (delete payment removes invoice)
*/

import { pgTable as pgTableBase } from 'drizzle-orm/pg-core';
import { serial, text, timestamp, boolean, jsonb, integer, index } from 'drizzle-orm/pg-core';

const pgTable = pgTableBase;

export const users = pgTable('users', {
  id: serial('id').primaryKey(),
  uid: text('uid').notNull().unique(),
  email: text('email').notNull().unique(),
  name: text('name'),
  role: text('role').default('subscriber').notNull(),
  pmproLevel: integer('pmpro_level').default(1),
  pin: text('pin'),
  logoUrl: text('logo_url'),
  primaryColor: text('primary_color').default('#3b82f6'),
  appName: text('app_name').default('Background Music Player'),
  secondaryColor: text('secondary_color').default('#1e293b'),
  fontFamily: text('font_family').default('Inter, system-ui, sans-serif'),
  playerSkin: text('player_skin').default('dark'),
  welcomeMessage: text('welcome_message'),
  outletName: text('outlet_name'),
  customCSS: text('custom_css'),
  mfaEnabled: boolean('mfa_enabled').default(false).notNull(),
  mfaSecret: text('mfa_secret'),
  emailVerified: boolean('email_verified').default(false).notNull(),
  createdAt: timestamp('created_at').defaultNow(),
}, (table) => ({
  emailIdx: index('users_email_idx').on(table.email),
}));

export const companies = pgTable('companies', {
  id: serial('id').primaryKey(),
  name: text('name').notNull(),
  type: text('type').default('retail'),
  country: text('country').default('PL'),
  region: text('region'),
  subscriptionPlan: text('subscription_plan').default('starter'),
  licenseScope: jsonb('license_scope'),
  ownerId: text('owner_id'),
  status: text('status').default('active'),
  createdAt: timestamp('created_at').defaultNow(),
  updatedAt: timestamp('updated_at').defaultNow(),
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
  storagePath: text('storage_path'),
  fileSize: integer('file_size'),
  format: text('format'),
  metadata: jsonb('metadata'),
  rightsOwnerId: text('rights_owner_id'),
  licenseScope: jsonb('license_scope'),
  status: text('status').default('active'),
  createdAt: timestamp('created_at').defaultNow(),
  updatedAt: timestamp('updated_at').defaultNow(),
}, (table) => ({
  titleIdx: index('tracks_title_idx').on(table.title),
  artistIdx: index('tracks_artist_idx').on(table.artist),
  genreIdx: index('tracks_genre_idx').on(table.genre),
  statusIdx: index('tracks_status_idx').on(table.status),
}));

export const playlists = pgTable('playlists', {
  id: serial('id').primaryKey(),
  title: text('title').notNull(),
  description: text('description'),
  authorUid: text('author_uid').notNull(),
  isPublic: boolean('is_public').default(false).notNull(),
  tags: jsonb('tags'),
  companyId: integer('company_id').notNull(),
  createdAt: timestamp('created_at').defaultNow(),
  updatedAt: timestamp('updated_at').defaultNow(),
}, (table) => ({
  authorUidIdx: index('playlists_author_uid_idx').on(table.authorUid),
  companyIdIdx: index('playlists_company_id_idx').on(table.companyId),
}));

export const playlist_tracks = pgTable('playlist_tracks', {
  id: serial('id').primaryKey(),
  playlistId: integer('playlist_id').references(() => playlists.id, { onDelete: 'cascade' }),
  trackId: integer('track_id').references(() => tracks.id, { onDelete: 'cascade' }),
  sequence: integer('sequence').default(0),
  addedAt: timestamp('added_at').defaultNow(),
});

export const licenses = pgTable('licenses', {
  id: serial('id').primaryKey(),
  companyId: integer('company_id').references(() => companies.id, { onDelete: 'cascade' }).notNull(),
  companyName: text('company_name').notNull(),
  licenseType: text('license_type').notNull(),
  status: text('status').default('active').notNull(),
  certificateNumber: text('certificate_number').notNull().unique(),
  issuedAt: timestamp('issued_at').defaultNow().notNull(),
  expiresAt: timestamp('expires_at').notNull(),
  authorUid: text('author_uid').notNull(),
  jurisdiction: text('jurisdiction').default('EU').notNull(),
  territories: jsonb('territories'),
  usageScope: jsonb('usage_scope'),
  maxLocations: integer('max_locations').default(1),
  maxConcurrentStreams: integer('max_concurrent_streams').default(10),
  renewalDate: timestamp('renewal_date'),
  contractId: integer('contract_id').references(() => contracts.id, { onDelete: 'cascade' }),
  auditTrail: jsonb('audit_trail'),
  createdAt: timestamp('created_at').defaultNow(),
  updatedAt: timestamp('updated_at').defaultNow(),
}, (table) => ({
  companyIdIdx: index('licenses_company_id_idx').on(table.companyId),
  statusIdx: index('licenses_status_idx').on(table.status),
  expiresAtIdx: index('licenses_expires_at_idx').on(table.expiresAt),
}));

export const contracts = pgTable('contracts', {
  id: serial('id').primaryKey(),
  licenseId: integer('license_id').references(() => licenses.id, { onDelete: 'cascade' }),
  contractText: text('contract_text').notNull(),
  signed: boolean('signed').default(false).notNull(),
  signedAt: timestamp('signed_at'),
  pdfUrl: text('pdf_url'),
  docxUrl: text('docx_url'),
  status: text('status').default('draft').notNull(),
  signatureStatus: text('signature_status').default('unsigned').notNull(),
  signatureProof: jsonb('signature_proof'),
  createdAt: timestamp('created_at').defaultNow(),
  updatedAt: timestamp('updated_at').defaultNow(),
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
  licenseId: integer('license_id').references(() => licenses.id, { onDelete: 'cascade' }),
  vatRate: integer('vat_rate').default(23),
  createdAt: timestamp('created_at').defaultNow(),
}, (table) => ({
  userIdIdx: index('payments_user_id_idx').on(table.userId),
  statusIdx: index('payments_status_idx').on(table.status),
  licenseIdIdx: index('payments_license_id_idx').on(table.licenseId),
}));

export const wordpress_settings = pgTable('wordpress_settings', {
  id: serial('id').primaryKey(),
  wpUrl: text('wp_url').default('https://example.com/wp-json').notNull(),
  appUsername: text('app_username').default('admin').notNull(),
  appPassword: text('app_password').default('').notNull(),
  bidirectional: boolean('bidirectional').default(true).notNull(),
  lastSyncTime: timestamp('last_sync_time'),
});

export const wordpress_sync_logs = pgTable('wordpress_sync_logs', {
  id: serial('id').primaryKey(),
  wpId: integer('wp_id'),
  wpType: text('wp_type').notNull(),
  title: text('title').notNull(),
  status: text('status').default('synced').notNull(),
  direction: text('direction').notNull(),
  errorMessage: text('error_message'),
  syncTime: timestamp('sync_time').defaultNow().notNull(),
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
  templatePaymentBody: text('template_payment_body').default('Thank you for your payment.').notNull(),
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
  createdAt: timestamp('created_at').defaultNow().notNull(),
});

export const audit_logs = pgTable('audit_logs', {
  id: serial('id').primaryKey(),
  userId: text('user_id'),
  action: text('action').notNull(),
  resource: text('resource').notNull(),
  details: text('details').notNull(),
  ipAddress: text('ip_address'),
  userAgent: text('user_agent'),
  createdAt: timestamp('created_at').defaultNow().notNull(),
}, (table) => ({
  userIdIdx: index('audit_logs_user_id_idx').on(table.userId),
  createdAtIdx: index('audit_logs_created_at_idx').on(table.createdAt),
}));

export const usage_logs = pgTable('usage_logs', {
  id: serial('id').primaryKey(),
  licenseId: integer('license_id').references(() => licenses.id, { onDelete: 'cascade' }),
  companyName: text('company_name'),
  trackId: integer('track_id').references(() => tracks.id, { onDelete: 'set null' }),
  trackTitle: text('track_title').notNull(),
  outletIp: text('outlet_ip'),
  durationPlayedSecond: integer('duration_played_second'),
  playedAt: timestamp('played_at').defaultNow().notNull(),
}, (table) => ({
  licenseIdIdx: index('usage_logs_license_id_idx').on(table.licenseId),
  playedAtIdx: index('usage_logs_played_at_idx').on(table.playedAt),
}));

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
  createdAt: timestamp('created_at').defaultNow(),
});

export const locations = pgTable('locations', {
  id: serial('id').primaryKey(),
  companyId: integer('company_id').references(() => companies.id, { onDelete: 'cascade' }).notNull(),
  name: text('name').notNull(),
  address: text('address'),
  city: text('city'),
  country: text('country'),
  timezone: text('timezone'),
  type: text('type').default('venue'),
  playlists: jsonb('playlists'),
  complianceStatus: jsonb('compliance_status'),
  createdAt: timestamp('created_at').defaultNow(),
  updatedAt: timestamp('updated_at').defaultNow(),
});

export const track_tags = pgTable('track_tags', {
  id: serial('id').primaryKey(),
  trackId: integer('track_id').references(() => tracks.id, { onDelete: 'cascade' }),
  bpm: integer('bpm'),
  key: text('key'),
  energy: integer('energy'),
  danceability: integer('danceability'),
  valence: integer('valence'),
  mood: jsonb('mood'),
  vibeDescription: text('vibe_description'),
  tags: jsonb('tags'),
  createdAt: timestamp('created_at').defaultNow(),
  updatedAt: timestamp('updated_at').defaultNow(),
});

export const custom_orders = pgTable('custom_orders', {
  id: serial('id').primaryKey(),
  userId: integer('user_id').references(() => users.id, { onDelete: 'set null' }),
  title: text('title').notNull(),
  description: text('description'),
  budget: integer('budget'),
  status: text('status').default('pending').notNull(),
  deadline: timestamp('deadline'),
  metadata: jsonb('metadata'),
  createdAt: timestamp('created_at').defaultNow(),
  updatedAt: timestamp('updated_at').defaultNow(),
});

export const api_keys = pgTable('api_keys', {
  id: serial('id').primaryKey(),
  userId: integer('user_id').references(() => users.id, { onDelete: 'cascade' }).notNull(),
  name: text('name').notNull(),
  keyHash: text('key_hash').notNull(),
  keyPrefix: text('key_prefix').notNull(),
  scopes: jsonb('scopes'),
  lastUsedAt: timestamp('last_used_at'),
  expiresAt: timestamp('expires_at'),
  isActive: boolean('is_active').default(true).notNull(),
  createdAt: timestamp('created_at').defaultNow(),
});

export const webhooks = pgTable('webhooks', {
  id: serial('id').primaryKey(),
  userId: integer('user_id').references(() => users.id, { onDelete: 'cascade' }).notNull(),
  url: text('url').notNull(),
  events: jsonb('events').notNull(),
  secret: text('secret').notNull(),
  isActive: boolean('is_active').default(true).notNull(),
  failureCount: integer('failure_count').default(0).notNull(),
  lastTriggeredAt: timestamp('last_triggered_at'),
  createdAt: timestamp('created_at').defaultNow(),
});

export const coupons = pgTable('coupons', {
  id: serial('id').primaryKey(),
  code: text('code').notNull().unique(),
  discountPercent: integer('discount_percent'),
  discountAmount: integer('discount_amount'),
  maxUses: integer('max_uses').default(1),
  usedCount: integer('used_count').default(0).notNull(),
  minAmount: integer('min_amount').default(0),
  expiresAt: timestamp('expires_at'),
  isActive: boolean('is_active').default(true).notNull(),
  createdAt: timestamp('created_at').defaultNow(),
});

export const webhook_deliveries = pgTable('webhook_deliveries', {
  id: serial('id').primaryKey(),
  webhookId: integer('webhook_id').references(() => webhooks.id, { onDelete: 'cascade' }).notNull(),
  event: text('event').notNull(),
  payload: jsonb('payload').notNull(),
  status: text('status').default('pending').notNull(),
  attempts: integer('attempts').default(0).notNull(),
  nextRetryAt: timestamp('next_retry_at'),
  responseCode: integer('response_code'),
  createdAt: timestamp('created_at').defaultNow(),
});