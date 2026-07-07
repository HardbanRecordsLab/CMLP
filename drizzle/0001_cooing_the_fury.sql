CREATE TABLE "api_keys" (
	"id" serial PRIMARY KEY NOT NULL,
	"user_id" integer NOT NULL,
	"name" text NOT NULL,
	"key_hash" text NOT NULL,
	"key_prefix" text NOT NULL,
	"scopes" jsonb,
	"last_used_at" timestamp,
	"expires_at" timestamp,
	"is_active" boolean DEFAULT true NOT NULL,
	"created_at" timestamp DEFAULT now()
);
--> statement-breakpoint
CREATE TABLE "custom_orders" (
	"id" serial PRIMARY KEY NOT NULL,
	"user_id" integer,
	"title" text NOT NULL,
	"description" text,
	"budget" integer,
	"status" text DEFAULT 'pending' NOT NULL,
	"deadline" timestamp,
	"metadata" jsonb,
	"created_at" timestamp DEFAULT now(),
	"updated_at" timestamp DEFAULT now()
);
--> statement-breakpoint
CREATE TABLE "locations" (
	"id" serial PRIMARY KEY NOT NULL,
	"company_id" integer NOT NULL,
	"name" text NOT NULL,
	"address" text,
	"city" text,
	"country" text,
	"timezone" text,
	"type" text DEFAULT 'venue',
	"playlists" jsonb,
	"compliance_status" jsonb,
	"created_at" timestamp DEFAULT now(),
	"updated_at" timestamp DEFAULT now()
);
--> statement-breakpoint
CREATE TABLE "track_tags" (
	"id" serial PRIMARY KEY NOT NULL,
	"track_id" integer,
	"bpm" integer,
	"key" text,
	"energy" integer,
	"danceability" integer,
	"valence" integer,
	"mood" jsonb,
	"vibe_description" text,
	"tags" jsonb,
	"created_at" timestamp DEFAULT now(),
	"updated_at" timestamp DEFAULT now()
);
--> statement-breakpoint
CREATE TABLE "webhook_deliveries" (
	"id" serial PRIMARY KEY NOT NULL,
	"webhook_id" integer NOT NULL,
	"event" text NOT NULL,
	"payload" jsonb NOT NULL,
	"status" text DEFAULT 'pending' NOT NULL,
	"attempts" integer DEFAULT 0 NOT NULL,
	"next_retry_at" timestamp,
	"response_code" integer,
	"created_at" timestamp DEFAULT now()
);
--> statement-breakpoint
CREATE TABLE "webhooks" (
	"id" serial PRIMARY KEY NOT NULL,
	"user_id" integer NOT NULL,
	"url" text NOT NULL,
	"events" jsonb NOT NULL,
	"secret" text NOT NULL,
	"is_active" boolean DEFAULT true NOT NULL,
	"failure_count" integer DEFAULT 0 NOT NULL,
	"last_triggered_at" timestamp,
	"created_at" timestamp DEFAULT now()
);
--> statement-breakpoint
ALTER TABLE "payments" DROP CONSTRAINT "payments_license_id_licenses_id_fk";
--> statement-breakpoint
DROP INDEX "payments_gateway_transaction_idx";--> statement-breakpoint
DROP INDEX "playlists_title_idx";--> statement-breakpoint
DROP INDEX "tracks_title_idx";--> statement-breakpoint
DROP INDEX "tracks_artist_idx";--> statement-breakpoint
DROP INDEX "tracks_genre_idx";--> statement-breakpoint
ALTER TABLE "audit_logs" ADD COLUMN "user_agent" text;--> statement-breakpoint
ALTER TABLE "companies" ADD COLUMN "type" text DEFAULT 'retail';--> statement-breakpoint
ALTER TABLE "companies" ADD COLUMN "country" text DEFAULT 'PL';--> statement-breakpoint
ALTER TABLE "companies" ADD COLUMN "region" text;--> statement-breakpoint
ALTER TABLE "companies" ADD COLUMN "subscription_plan" text DEFAULT 'starter';--> statement-breakpoint
ALTER TABLE "companies" ADD COLUMN "license_scope" jsonb;--> statement-breakpoint
ALTER TABLE "companies" ADD COLUMN "owner_id" text;--> statement-breakpoint
ALTER TABLE "companies" ADD COLUMN "status" text DEFAULT 'active';--> statement-breakpoint
ALTER TABLE "companies" ADD COLUMN "updated_at" timestamp DEFAULT now();--> statement-breakpoint
ALTER TABLE "contracts" ADD COLUMN "pdf_url" text;--> statement-breakpoint
ALTER TABLE "contracts" ADD COLUMN "docx_url" text;--> statement-breakpoint
ALTER TABLE "contracts" ADD COLUMN "status" text DEFAULT 'draft' NOT NULL;--> statement-breakpoint
ALTER TABLE "contracts" ADD COLUMN "signature_status" text DEFAULT 'unsigned' NOT NULL;--> statement-breakpoint
ALTER TABLE "contracts" ADD COLUMN "signature_proof" jsonb;--> statement-breakpoint
ALTER TABLE "contracts" ADD COLUMN "updated_at" timestamp DEFAULT now();--> statement-breakpoint
ALTER TABLE "licenses" ADD COLUMN "company_id" integer NOT NULL;--> statement-breakpoint
ALTER TABLE "licenses" ADD COLUMN "territories" jsonb;--> statement-breakpoint
ALTER TABLE "licenses" ADD COLUMN "usage_scope" jsonb;--> statement-breakpoint
ALTER TABLE "licenses" ADD COLUMN "max_locations" integer DEFAULT 1;--> statement-breakpoint
ALTER TABLE "licenses" ADD COLUMN "max_concurrent_streams" integer DEFAULT 10;--> statement-breakpoint
ALTER TABLE "licenses" ADD COLUMN "renewal_date" timestamp;--> statement-breakpoint
ALTER TABLE "licenses" ADD COLUMN "contract_id" integer;--> statement-breakpoint
ALTER TABLE "licenses" ADD COLUMN "audit_trail" jsonb;--> statement-breakpoint
ALTER TABLE "licenses" ADD COLUMN "updated_at" timestamp DEFAULT now();--> statement-breakpoint
ALTER TABLE "playlist_tracks" ADD COLUMN "added_at" timestamp DEFAULT now();--> statement-breakpoint
ALTER TABLE "playlists" ADD COLUMN "tags" jsonb;--> statement-breakpoint
ALTER TABLE "playlists" ADD COLUMN "company_id" integer NOT NULL;--> statement-breakpoint
ALTER TABLE "playlists" ADD COLUMN "updated_at" timestamp DEFAULT now();--> statement-breakpoint
ALTER TABLE "tracks" ADD COLUMN "storage_path" text;--> statement-breakpoint
ALTER TABLE "tracks" ADD COLUMN "file_size" integer;--> statement-breakpoint
ALTER TABLE "tracks" ADD COLUMN "format" text;--> statement-breakpoint
ALTER TABLE "tracks" ADD COLUMN "metadata" jsonb;--> statement-breakpoint
ALTER TABLE "tracks" ADD COLUMN "rights_owner_id" text;--> statement-breakpoint
ALTER TABLE "tracks" ADD COLUMN "license_scope" jsonb;--> statement-breakpoint
ALTER TABLE "tracks" ADD COLUMN "status" text DEFAULT 'active';--> statement-breakpoint
ALTER TABLE "tracks" ADD COLUMN "updated_at" timestamp DEFAULT now();--> statement-breakpoint
ALTER TABLE "usage_logs" ADD COLUMN "license_id" integer;--> statement-breakpoint
ALTER TABLE "users" ADD COLUMN "secondary_color" text DEFAULT '#1e293b';--> statement-breakpoint
ALTER TABLE "users" ADD COLUMN "font_family" text DEFAULT 'Inter, system-ui, sans-serif';--> statement-breakpoint
ALTER TABLE "users" ADD COLUMN "player_skin" text DEFAULT 'dark';--> statement-breakpoint
ALTER TABLE "users" ADD COLUMN "welcome_message" text;--> statement-breakpoint
ALTER TABLE "users" ADD COLUMN "outlet_name" text;--> statement-breakpoint
ALTER TABLE "users" ADD COLUMN "custom_css" text;--> statement-breakpoint
ALTER TABLE "api_keys" ADD CONSTRAINT "api_keys_user_id_users_id_fk" FOREIGN KEY ("user_id") REFERENCES "public"."users"("id") ON DELETE cascade ON UPDATE no action;--> statement-breakpoint
ALTER TABLE "custom_orders" ADD CONSTRAINT "custom_orders_user_id_users_id_fk" FOREIGN KEY ("user_id") REFERENCES "public"."users"("id") ON DELETE set null ON UPDATE no action;--> statement-breakpoint
ALTER TABLE "locations" ADD CONSTRAINT "locations_company_id_companies_id_fk" FOREIGN KEY ("company_id") REFERENCES "public"."companies"("id") ON DELETE cascade ON UPDATE no action;--> statement-breakpoint
ALTER TABLE "track_tags" ADD CONSTRAINT "track_tags_track_id_tracks_id_fk" FOREIGN KEY ("track_id") REFERENCES "public"."tracks"("id") ON DELETE cascade ON UPDATE no action;--> statement-breakpoint
ALTER TABLE "webhook_deliveries" ADD CONSTRAINT "webhook_deliveries_webhook_id_webhooks_id_fk" FOREIGN KEY ("webhook_id") REFERENCES "public"."webhooks"("id") ON DELETE cascade ON UPDATE no action;--> statement-breakpoint
ALTER TABLE "webhooks" ADD CONSTRAINT "webhooks_user_id_users_id_fk" FOREIGN KEY ("user_id") REFERENCES "public"."users"("id") ON DELETE cascade ON UPDATE no action;--> statement-breakpoint
ALTER TABLE "licenses" ADD CONSTRAINT "licenses_company_id_companies_id_fk" FOREIGN KEY ("company_id") REFERENCES "public"."companies"("id") ON DELETE cascade ON UPDATE no action;--> statement-breakpoint
ALTER TABLE "licenses" ADD CONSTRAINT "licenses_contract_id_contracts_id_fk" FOREIGN KEY ("contract_id") REFERENCES "public"."contracts"("id") ON DELETE cascade ON UPDATE no action;--> statement-breakpoint
ALTER TABLE "payments" ADD CONSTRAINT "payments_license_id_licenses_id_fk" FOREIGN KEY ("license_id") REFERENCES "public"."licenses"("id") ON DELETE cascade ON UPDATE no action;--> statement-breakpoint
ALTER TABLE "usage_logs" ADD CONSTRAINT "usage_logs_license_id_licenses_id_fk" FOREIGN KEY ("license_id") REFERENCES "public"."licenses"("id") ON DELETE cascade ON UPDATE no action;