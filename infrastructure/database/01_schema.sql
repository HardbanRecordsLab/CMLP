-- CMLP Database Schema - PostgreSQL 16+
-- Run this file to initialize the database in production

-- Enable required extensions
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- Companies table (for multi-location support)
CREATE TABLE IF NOT EXISTS companies (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    type TEXT DEFAULT 'retail',
    country TEXT DEFAULT 'PL',
    region TEXT,
    subscription_plan TEXT DEFAULT 'starter',
    license_scope JSONB,
    owner_id TEXT,
    status TEXT DEFAULT 'active',
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    uid TEXT NOT NULL UNIQUE,
    email TEXT NOT NULL,
    name TEXT,
    role TEXT DEFAULT 'subscriber' NOT NULL,
    pmpro_level INTEGER DEFAULT 1,
    pin TEXT,
    logo_url TEXT,
    primary_color TEXT DEFAULT '#3b82f6',
    app_name TEXT DEFAULT 'Background Music Player',
    secondary_color TEXT DEFAULT '#1e293b',
    font_family TEXT DEFAULT 'Inter, system-ui, sans-serif',
    player_skin TEXT DEFAULT 'dark',
    welcome_message TEXT,
    outlet_name TEXT,
    custom_css TEXT,
    mfa_enabled BOOLEAN DEFAULT FALSE NOT NULL,
    mfa_secret TEXT,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Create index on users.email for fast lookups
CREATE INDEX IF NOT EXISTS idx_users_email ON users(email);

-- Tracks table (audio_files)
CREATE TABLE IF NOT EXISTS tracks (
    id SERIAL PRIMARY KEY,
    title TEXT NOT NULL,
    artist TEXT NOT NULL,
    album TEXT,
    year INTEGER,
    bpm INTEGER,
    genre TEXT,
    mood JSONB,
    duration_ms INTEGER,
    explicit BOOLEAN DEFAULT FALSE,
    time_of_day JSONB,
    isrc TEXT,
    cover_url TEXT,
    filename TEXT NOT NULL,
    storage_path TEXT,
    file_size BIGINT,
    format TEXT,
    metadata JSONB,
    rights_owner_id TEXT,
    license_scope JSONB,
    status TEXT DEFAULT 'active',
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

-- Create indexes for tracks
CREATE INDEX IF NOT EXISTS idx_tracks_artist ON tracks(artist);
CREATE INDEX IF NOT EXISTS idx_tracks_genre ON tracks(genre);
CREATE INDEX IF NOT EXISTS idx_tracks_isrc ON tracks(isrc);
CREATE INDEX IF NOT EXISTS idx_tracks_created_at ON tracks(created_at DESC);
CREATE INDEX IF NOT EXISTS idx_tracks_bpm ON tracks(bpm);
CREATE INDEX IF NOT EXISTS idx_tracks_year ON tracks(year);
CREATE INDEX IF NOT EXISTS idx_tracks_time_of_day ON tracks(time_of_day);

-- Playlists table
CREATE TABLE IF NOT EXISTS playlists (
    id SERIAL PRIMARY KEY,
    title TEXT NOT NULL,
    description TEXT,
    author_uid TEXT NOT NULL,
    is_public BOOLEAN DEFAULT FALSE NOT NULL,
    tags JSONB,
    company_id INTEGER REFERENCES companies(id) ON DELETE CASCADE NOT NULL,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

CREATE INDEX IF NOT EXISTS idx_playlists_author_uid ON playlists(author_uid);
CREATE INDEX IF NOT EXISTS idx_playlists_company_id ON playlists(company_id);
CREATE INDEX IF NOT EXISTS idx_playlists_is_public_created_at ON playlists(is_public, created_at DESC);

-- Playlist tracks mapping
CREATE TABLE IF NOT EXISTS playlist_tracks (
    id SERIAL PRIMARY KEY,
    playlist_id INTEGER REFERENCES playlists(id) ON DELETE CASCADE,
    track_id INTEGER REFERENCES tracks(id) ON DELETE CASCADE,
    sequence INTEGER DEFAULT 0,
    added_at TIMESTAMP DEFAULT NOW()
);

-- Licenses table
CREATE TABLE IF NOT EXISTS licenses (
    id SERIAL PRIMARY KEY,
    company_id INTEGER REFERENCES companies(id) ON DELETE CASCADE NOT NULL,
    company_name TEXT NOT NULL,
    license_type TEXT NOT NULL,
    status TEXT DEFAULT 'active' NOT NULL,
    certificate_number TEXT NOT NULL UNIQUE,
    issued_at TIMESTAMP DEFAULT NOW() NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    author_uid TEXT NOT NULL,
    jurisdiction TEXT DEFAULT 'EU' NOT NULL,
    territories JSONB,
    usage_scope JSONB,
    max_locations INTEGER DEFAULT 1,
    max_concurrent_streams INTEGER DEFAULT 10,
    renewal_date TIMESTAMP,
    contract_id INTEGER REFERENCES contracts(id) ON DELETE CASCADE,
    audit_trail JSONB,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

CREATE INDEX IF NOT EXISTS idx_licenses_company_id ON licenses(company_id);
CREATE INDEX IF NOT EXISTS idx_licenses_company_name ON licenses(company_name);
CREATE INDEX IF NOT EXISTS idx_licenses_status ON licenses(status);
CREATE INDEX IF NOT EXISTS idx_licenses_end_date ON licenses(expires_at);
CREATE INDEX IF NOT EXISTS idx_licenses_issued_at ON licenses(issued_at);
CREATE INDEX IF NOT EXISTS idx_licenses_author_uid ON licenses(author_uid);

-- Contracts table
CREATE TABLE IF NOT EXISTS contracts (
    id SERIAL PRIMARY KEY,
    license_id INTEGER REFERENCES licenses(id) ON DELETE CASCADE,
    contract_text TEXT NOT NULL,
    signed BOOLEAN DEFAULT FALSE NOT NULL,
    signed_at TIMESTAMP,
    pdf_url TEXT,
    docx_url TEXT,
    status TEXT DEFAULT 'draft' NOT NULL,
    signature_status TEXT DEFAULT 'unsigned' NOT NULL,
    signature_proof JSONB,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

-- Payments table
CREATE TABLE IF NOT EXISTS payments (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON DELETE SET NULL,
    amount INTEGER NOT NULL,
    currency TEXT DEFAULT 'PLN' NOT NULL,
    gateway TEXT NOT NULL,
    transaction_type TEXT NOT NULL,
    status TEXT DEFAULT 'pending' NOT NULL,
    gateway_transaction_id TEXT,
    license_id INTEGER REFERENCES licenses(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT NOW()
);

CREATE INDEX IF NOT EXISTS idx_payments_user_id ON payments(user_id);
CREATE INDEX IF NOT EXISTS idx_payments_license_id ON payments(license_id);
CREATE INDEX IF NOT EXISTS idx_payments_status ON payments(status);

-- Usage logs table
CREATE TABLE IF NOT EXISTS usage_logs (
    id SERIAL PRIMARY KEY,
    license_id INTEGER REFERENCES licenses(id) ON DELETE CASCADE,
    company_name TEXT,
    track_id INTEGER REFERENCES tracks(id) ON DELETE SET NULL,
    track_title TEXT NOT NULL,
    outlet_ip TEXT,
    duration_played_second INTEGER,
    played_at TIMESTAMP DEFAULT NOW() NOT NULL
);

CREATE INDEX IF NOT EXISTS idx_usage_logs_license_id ON usage_logs(license_id);
CREATE INDEX IF NOT EXISTS idx_usage_logs_track_id ON usage_logs(track_id);
CREATE INDEX IF NOT EXISTS idx_usage_logs_played_at ON usage_logs(played_at DESC);

-- Audit logs table
CREATE TABLE IF NOT EXISTS audit_logs (
    id SERIAL PRIMARY KEY,
    user_id TEXT,
    action TEXT NOT NULL,
    resource TEXT NOT NULL,
    details TEXT NOT NULL,
    ip_address TEXT,
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT NOW() NOT NULL
);

CREATE INDEX IF NOT EXISTS idx_audit_logs_user_id ON audit_logs(user_id);
CREATE INDEX IF NOT EXISTS idx_audit_logs_created_at ON audit_logs(created_at DESC);
CREATE INDEX IF NOT EXISTS idx_audit_logs_action ON audit_logs(action);

-- Locations table
CREATE TABLE IF NOT EXISTS locations (
    id SERIAL PRIMARY KEY,
    company_id INTEGER REFERENCES companies(id) ON DELETE CASCADE,
    name TEXT NOT NULL,
    address TEXT,
    city TEXT,
    country TEXT,
    timezone TEXT,
    type TEXT DEFAULT 'venue',
    playlists JSONB,
    compliance_status JSONB,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

-- VOD content table
CREATE TABLE IF NOT EXISTS vod_content (
    id SERIAL PRIMARY KEY,
    title TEXT NOT NULL,
    description TEXT,
    filename TEXT NOT NULL,
    thumbnail_url TEXT,
    duration_ms INTEGER,
    mime_type TEXT DEFAULT 'video/mp4' NOT NULL,
    is_public BOOLEAN DEFAULT FALSE NOT NULL,
    author_uid TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT NOW()
);

-- WordPress settings
CREATE TABLE IF NOT EXISTS wordpress_settings (
    id SERIAL PRIMARY KEY,
    wp_url TEXT DEFAULT 'https://example.com/wp-json' NOT NULL,
    app_username TEXT DEFAULT 'admin' NOT NULL,
    app_password TEXT DEFAULT '' NOT NULL,
    bidirectional BOOLEAN DEFAULT TRUE NOT NULL,
    last_sync_time TIMESTAMP
);

-- WordPress sync logs
CREATE TABLE IF NOT EXISTS wordpress_sync_logs (
    id SERIAL PRIMARY KEY,
    wp_id INTEGER,
    wp_type TEXT NOT NULL,
    title TEXT NOT NULL,
    status TEXT DEFAULT 'synced' NOT NULL,
    direction TEXT NOT NULL,
    error_message TEXT,
    sync_time TIMESTAMP DEFAULT NOW() NOT NULL
);

-- Track tags table (AI-generated tags)
CREATE TABLE IF NOT EXISTS track_tags (
    id SERIAL PRIMARY KEY,
    track_id INTEGER REFERENCES tracks(id) ON DELETE CASCADE,
    bpm INTEGER,
    key TEXT,
    energy INTEGER,
    danceability INTEGER,
    valence INTEGER,
    mood JSONB,
    vibe_description TEXT,
    tags JSONB,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

-- Notification settings
CREATE TABLE IF NOT EXISTS notification_settings (
    id SERIAL PRIMARY KEY,
    provider TEXT DEFAULT 'smtp' NOT NULL,
    smtp_host TEXT DEFAULT 'smtp.mailtrap.io' NOT NULL,
    smtp_port INTEGER DEFAULT 587 NOT NULL,
    smtp_user TEXT DEFAULT '' NOT NULL,
    smtp_pass TEXT DEFAULT '' NOT NULL,
    sendgrid_api_key TEXT DEFAULT '' NOT NULL,
    from_email TEXT DEFAULT 'noreply@hrl.pl' NOT NULL,
    from_name TEXT DEFAULT 'Hardban Records Lab' NOT NULL,
    template_welcome_subject TEXT DEFAULT 'Welcome to Hardban Records Lab!' NOT NULL,
    template_welcome_body TEXT DEFAULT 'Hello, welcome to Hardban Records Lab!' NOT NULL,
    template_expiry_subject TEXT DEFAULT 'License Expiring Soon' NOT NULL,
    template_expiry_body TEXT DEFAULT 'Your license is expiring soon. Please renew.' NOT NULL,
    template_payment_subject TEXT DEFAULT 'Payment Receipt' NOT NULL,
    template_payment_body TEXT DEFAULT 'Thank you for your payment.' NOT NULL
);

-- Notification logs
CREATE TABLE IF NOT EXISTS notification_logs (
    id SERIAL PRIMARY KEY,
    channel TEXT NOT NULL,
    recipient TEXT NOT NULL,
    notification_type TEXT NOT NULL,
    subject TEXT NOT NULL,
    body TEXT NOT NULL,
    status TEXT DEFAULT 'sent' NOT NULL,
    error_message TEXT,
    created_at TIMESTAMP DEFAULT NOW() NOT NULL
);

-- Invoices table
CREATE TABLE IF NOT EXISTS invoices (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON DELETE SET NULL,
    amount INTEGER NOT NULL,
    status TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Seed initial admin users (password: 'Kskomra1984!!' - should be changed after deployment)
-- Note: Firebase handles actual authentication, this is for DB sync
INSERT INTO users (uid, email, name, role, app_name) 
VALUES 
    ('admin-hrl-uid-001', 'hardbanrecordslab.pl@gmail.com', 'Hardban Records Lab Admin', 'admin', 'HRL Admin Panel'),
    ('admin-fds-uid-002', 'familydreamshop.pl@gmail.com', 'Family Dream Shop Admin', 'admin', 'HRL Admin Panel')
ON CONFLICT (email) DO UPDATE SET role = EXCLUDED.role;

-- Insert default WordPress settings placeholder
INSERT INTO wordpress_settings (wp_url, app_username, app_password, bidirectional)
VALUES ('https://demo.hrl.pl/wp-json', 'admin', '', true)
ON CONFLICT DO NOTHING;

-- Custom music orders
CREATE TABLE IF NOT EXISTS custom_orders (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON DELETE SET NULL,
    title TEXT NOT NULL,
    description TEXT,
    budget INTEGER,
    status TEXT DEFAULT 'pending' NOT NULL,
    deadline TIMESTAMP,
    metadata JSONB,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

CREATE INDEX IF NOT EXISTS idx_custom_orders_user_id ON custom_orders(user_id);
CREATE INDEX IF NOT EXISTS idx_custom_orders_status ON custom_orders(status);

-- API keys (hashed, never store plain key)
CREATE TABLE IF NOT EXISTS api_keys (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON DELETE CASCADE NOT NULL,
    name TEXT NOT NULL,
    key_hash TEXT NOT NULL,
    key_prefix TEXT NOT NULL,
    scopes JSONB,
    last_used_at TIMESTAMP,
    expires_at TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE NOT NULL,
    created_at TIMESTAMP DEFAULT NOW()
);

CREATE INDEX IF NOT EXISTS idx_api_keys_user_id ON api_keys(user_id);
CREATE INDEX IF NOT EXISTS idx_api_keys_key_prefix ON api_keys(key_prefix);

-- Outbound webhooks manager
CREATE TABLE IF NOT EXISTS webhooks (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON DELETE CASCADE NOT NULL,
    url TEXT NOT NULL,
    events JSONB NOT NULL,
    secret TEXT NOT NULL,
    is_active BOOLEAN DEFAULT TRUE NOT NULL,
    failure_count INTEGER DEFAULT 0 NOT NULL,
    last_triggered_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT NOW()
);

CREATE INDEX IF NOT EXISTS idx_webhooks_user_id ON webhooks(user_id);

-- Webhook delivery retry tracking
CREATE TABLE IF NOT EXISTS webhook_deliveries (
    id SERIAL PRIMARY KEY,
    webhook_id INTEGER REFERENCES webhooks(id) ON DELETE CASCADE NOT NULL,
    event TEXT NOT NULL,
    payload JSONB NOT NULL,
    status TEXT DEFAULT 'pending' NOT NULL,
    attempts INTEGER DEFAULT 0 NOT NULL,
    next_retry_at TIMESTAMP,
    response_code INTEGER,
    created_at TIMESTAMP DEFAULT NOW()
);

CREATE INDEX IF NOT EXISTS idx_webhook_deliveries_webhook_id ON webhook_deliveries(webhook_id);
CREATE INDEX IF NOT EXISTS idx_webhook_deliveries_status ON webhook_deliveries(status);