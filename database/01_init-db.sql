-- ============================================================================
-- CMLP Database Initialization Script
-- Initial schema and seed data for development
-- ============================================================================

-- Create schema
CREATE SCHEMA IF NOT EXISTS cmlp;

-- Enable extensions
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pg_trgm";

-- Create base tables

-- Users table
CREATE TABLE cmlp.users (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  email VARCHAR(255) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  first_name VARCHAR(100),
  last_name VARCHAR(100),
  roles JSONB DEFAULT '["user"]'::jsonb,
  mfa_enabled BOOLEAN DEFAULT false,
  mfa_secret VARCHAR(255),
  status VARCHAR(20) DEFAULT 'active',
  last_login TIMESTAMP,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_users_email ON cmlp.users(email);
CREATE INDEX idx_users_status ON cmlp.users(status);
CREATE INDEX idx_users_created_at ON cmlp.users(created_at);

-- Companies table
CREATE TABLE cmlp.companies (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  name VARCHAR(255) NOT NULL,
  type VARCHAR(50),
  country VARCHAR(2),
  region VARCHAR(100),
  subscription_plan VARCHAR(50) DEFAULT 'starter',
  owner_id UUID NOT NULL REFERENCES cmlp.users(id) ON DELETE CASCADE,
  status VARCHAR(20) DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_companies_owner_id ON cmlp.companies(owner_id);
CREATE INDEX idx_companies_status ON cmlp.companies(status);

-- Locations table
CREATE TABLE cmlp.locations (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  company_id UUID NOT NULL REFERENCES cmlp.companies(id) ON DELETE CASCADE,
  name VARCHAR(255) NOT NULL,
  address VARCHAR(255),
  city VARCHAR(100),
  country VARCHAR(2),
  timezone VARCHAR(50),
  type VARCHAR(50),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_locations_company_id ON cmlp.locations(company_id);

-- Audio files table
CREATE TABLE cmlp.audio_files (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  title VARCHAR(255) NOT NULL,
  artist VARCHAR(255),
  album VARCHAR(255),
  genre VARCHAR(100),
  mood VARCHAR(100),
  bpm INT,
  key VARCHAR(10),
  duration INT,
  isrc_code VARCHAR(12) UNIQUE,
  storage_path VARCHAR(255) NOT NULL,
  file_size BIGINT,
  format VARCHAR(10),
  metadata JSONB,
  metadata_confidence DECIMAL(3,2),
  rights_owner_id UUID,
  status VARCHAR(20) DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_audio_files_artist ON cmlp.audio_files(artist);
CREATE INDEX idx_audio_files_genre ON cmlp.audio_files(genre);
CREATE INDEX idx_audio_files_mood ON cmlp.audio_files(mood);
CREATE INDEX idx_audio_files_isrc ON cmlp.audio_files(isrc_code);
CREATE INDEX idx_audio_files_created_at ON cmlp.audio_files(created_at DESC);

-- Playlists table
CREATE TABLE cmlp.playlists (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  owner_id UUID NOT NULL REFERENCES cmlp.users(id) ON DELETE CASCADE,
  company_id UUID REFERENCES cmlp.companies(id) ON DELETE SET NULL,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  tracks JSONB DEFAULT '[]'::jsonb,
  is_public BOOLEAN DEFAULT false,
  is_editable BOOLEAN DEFAULT true,
  tags TEXT[],
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_playlists_owner_id ON cmlp.playlists(owner_id);
CREATE INDEX idx_playlists_company_id ON cmlp.playlists(company_id);

-- Licenses table
CREATE TABLE cmlp.licenses (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  company_id UUID NOT NULL REFERENCES cmlp.companies(id) ON DELETE CASCADE,
  license_type VARCHAR(50),
  territories TEXT[],
  usage_scope JSONB,
  max_locations INT,
  max_concurrent_streams INT,
  start_date TIMESTAMP NOT NULL,
  end_date TIMESTAMP NOT NULL,
  renewal_date TIMESTAMP,
  contract_id UUID,
  status VARCHAR(20) DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_licenses_company_id ON cmlp.licenses(company_id);
CREATE INDEX idx_licenses_status ON cmlp.licenses(status);
CREATE INDEX idx_licenses_end_date ON cmlp.licenses(end_date);

-- Payments table
CREATE TABLE cmlp.payments (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  company_id UUID NOT NULL REFERENCES cmlp.companies(id) ON DELETE CASCADE,
  license_id UUID REFERENCES cmlp.licenses(id) ON DELETE SET NULL,
  amount DECIMAL(10,2) NOT NULL,
  currency VARCHAR(3) DEFAULT 'PLN',
  payment_method VARCHAR(50),
  transaction_id VARCHAR(255),
  status VARCHAR(20) DEFAULT 'pending',
  metadata JSONB,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_payments_company_id ON cmlp.payments(company_id);
CREATE INDEX idx_payments_status ON cmlp.payments(status);

-- Usage logs table (partitioned by date for performance)
CREATE TABLE cmlp.usage_logs (
  id BIGSERIAL PRIMARY KEY,
  license_id UUID NOT NULL REFERENCES cmlp.licenses(id) ON DELETE CASCADE,
  location_id UUID REFERENCES cmlp.locations(id) ON DELETE SET NULL,
  track_id UUID REFERENCES cmlp.audio_files(id) ON DELETE SET NULL,
  play_count INT DEFAULT 1,
  timestamp DATE DEFAULT CURRENT_DATE,
  device_type VARCHAR(50),
  user_agent TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_usage_logs_license_id ON cmlp.usage_logs(license_id);
CREATE INDEX idx_usage_logs_timestamp ON cmlp.usage_logs(timestamp);
CREATE INDEX idx_usage_logs_track_id ON cmlp.usage_logs(track_id);

-- Audit logs table
CREATE TABLE cmlp.audit_logs (
  id BIGSERIAL PRIMARY KEY,
  user_id UUID REFERENCES cmlp.users(id) ON DELETE SET NULL,
  action VARCHAR(50) NOT NULL,
  resource_type VARCHAR(50) NOT NULL,
  resource_id UUID,
  changes JSONB,
  ip_address VARCHAR(45),
  user_agent TEXT,
  timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_audit_logs_user_id ON cmlp.audit_logs(user_id);
CREATE INDEX idx_audit_logs_timestamp ON cmlp.audit_logs(timestamp);
CREATE INDEX idx_audit_logs_action ON cmlp.audit_logs(action);

-- Contracts table
CREATE TABLE cmlp.contracts (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  license_id UUID NOT NULL REFERENCES cmlp.licenses(id) ON DELETE CASCADE,
  company_id UUID NOT NULL REFERENCES cmlp.companies(id) ON DELETE CASCADE,
  template_id VARCHAR(255),
  document_content LONGTEXT,
  pdf_url VARCHAR(255),
  status VARCHAR(20) DEFAULT 'draft',
  signature_status VARCHAR(20) DEFAULT 'unsigned',
  signed_date TIMESTAMP,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_contracts_license_id ON cmlp.contracts(license_id);
CREATE INDEX idx_contracts_company_id ON cmlp.contracts(company_id);
CREATE INDEX idx_contracts_status ON cmlp.contracts(status);

-- Create views
CREATE VIEW cmlp.active_licenses AS
SELECT * FROM cmlp.licenses
WHERE status = 'active' AND end_date > CURRENT_TIMESTAMP;

CREATE VIEW cmlp.expiring_licenses AS
SELECT * FROM cmlp.licenses
WHERE status = 'active' AND end_date BETWEEN CURRENT_TIMESTAMP AND CURRENT_TIMESTAMP + INTERVAL '30 days';

-- Create function for updating updated_at timestamp
CREATE OR REPLACE FUNCTION cmlp.update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
  NEW.updated_at = CURRENT_TIMESTAMP;
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Apply trigger to tables
CREATE TRIGGER update_users_updated_at BEFORE UPDATE ON cmlp.users
FOR EACH ROW EXECUTE FUNCTION cmlp.update_updated_at_column();

CREATE TRIGGER update_companies_updated_at BEFORE UPDATE ON cmlp.companies
FOR EACH ROW EXECUTE FUNCTION cmlp.update_updated_at_column();

CREATE TRIGGER update_audio_files_updated_at BEFORE UPDATE ON cmlp.audio_files
FOR EACH ROW EXECUTE FUNCTION cmlp.update_updated_at_column();

CREATE TRIGGER update_playlists_updated_at BEFORE UPDATE ON cmlp.playlists
FOR EACH ROW EXECUTE FUNCTION cmlp.update_updated_at_column();

CREATE TRIGGER update_licenses_updated_at BEFORE UPDATE ON cmlp.licenses
FOR EACH ROW EXECUTE FUNCTION cmlp.update_updated_at_column();

-- Seed test data (development only)
INSERT INTO cmlp.users (email, password_hash, first_name, last_name, roles)
VALUES (
  'admin@example.com',
  '$2a$12$...',  -- bcrypt hash of 'password123' - replace with actual hash
  'Admin',
  'User',
  '["admin"]'::jsonb
) ON CONFLICT (email) DO NOTHING;

-- Grant permissions
GRANT USAGE ON SCHEMA cmlp TO postgres;
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA cmlp TO postgres;
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA cmlp TO postgres;

-- Done
\echo 'CMLP Database initialized successfully!'
