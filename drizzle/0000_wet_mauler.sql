CREATE TABLE "audit_logs" (
	"id" serial PRIMARY KEY NOT NULL,
	"user_id" text,
	"action" text NOT NULL,
	"resource" text NOT NULL,
	"details" text NOT NULL,
	"ip_address" text,
	"created_at" timestamp DEFAULT now() NOT NULL
);
--> statement-breakpoint
CREATE TABLE "companies" (
	"id" serial PRIMARY KEY NOT NULL,
	"name" text NOT NULL,
	"created_at" timestamp DEFAULT now()
);
--> statement-breakpoint
CREATE TABLE "contracts" (
	"id" serial PRIMARY KEY NOT NULL,
	"license_id" integer,
	"contract_text" text NOT NULL,
	"signed" boolean DEFAULT false NOT NULL,
	"signed_at" timestamp,
	"created_at" timestamp DEFAULT now()
);
--> statement-breakpoint
CREATE TABLE "invoices" (
	"id" serial PRIMARY KEY NOT NULL,
	"user_id" integer,
	"amount" integer NOT NULL,
	"status" text NOT NULL,
	"created_at" timestamp DEFAULT now()
);
--> statement-breakpoint
CREATE TABLE "licenses" (
	"id" serial PRIMARY KEY NOT NULL,
	"company_name" text NOT NULL,
	"license_type" text NOT NULL,
	"status" text DEFAULT 'active' NOT NULL,
	"certificate_number" text NOT NULL,
	"issued_at" timestamp DEFAULT now() NOT NULL,
	"expires_at" timestamp NOT NULL,
	"author_uid" text NOT NULL,
	"jurisdiction" text DEFAULT 'EU' NOT NULL,
	"created_at" timestamp DEFAULT now(),
	CONSTRAINT "licenses_certificate_number_unique" UNIQUE("certificate_number")
);
--> statement-breakpoint
CREATE TABLE "notification_logs" (
	"id" serial PRIMARY KEY NOT NULL,
	"channel" text NOT NULL,
	"recipient" text NOT NULL,
	"notification_type" text NOT NULL,
	"subject" text NOT NULL,
	"body" text NOT NULL,
	"status" text DEFAULT 'sent' NOT NULL,
	"error_message" text,
	"created_at" timestamp DEFAULT now() NOT NULL
);
--> statement-breakpoint
CREATE TABLE "notification_settings" (
	"id" serial PRIMARY KEY NOT NULL,
	"provider" text DEFAULT 'smtp' NOT NULL,
	"smtp_host" text DEFAULT 'smtp.mailtrap.io' NOT NULL,
	"smtp_port" integer DEFAULT 587 NOT NULL,
	"smtp_user" text DEFAULT '' NOT NULL,
	"smtp_pass" text DEFAULT '' NOT NULL,
	"sendgrid_api_key" text DEFAULT '' NOT NULL,
	"from_email" text DEFAULT 'noreply@hrl.pl' NOT NULL,
	"from_name" text DEFAULT 'Hardban Records Lab' NOT NULL,
	"template_welcome_subject" text DEFAULT 'Welcome to Hardban Records Lab!' NOT NULL,
	"template_welcome_body" text DEFAULT 'Hello, welcome to Hardban Records Lab!' NOT NULL,
	"template_expiry_subject" text DEFAULT 'License Expiring Soon' NOT NULL,
	"template_expiry_body" text DEFAULT 'Your license is expiring soon.' NOT NULL,
	"template_payment_subject" text DEFAULT 'Payment Receipt' NOT NULL,
	"template_payment_body" text DEFAULT 'Thank you for your payment.' NOT NULL
);
--> statement-breakpoint
CREATE TABLE "payments" (
	"id" serial PRIMARY KEY NOT NULL,
	"user_id" integer,
	"amount" integer NOT NULL,
	"currency" text DEFAULT 'PLN' NOT NULL,
	"gateway" text NOT NULL,
	"transaction_type" text NOT NULL,
	"status" text DEFAULT 'pending' NOT NULL,
	"gateway_transaction_id" text,
	"license_id" integer,
	"created_at" timestamp DEFAULT now()
);
--> statement-breakpoint
CREATE TABLE "playlist_tracks" (
	"id" serial PRIMARY KEY NOT NULL,
	"playlist_id" integer,
	"track_id" integer,
	"sequence" integer DEFAULT 0
);
--> statement-breakpoint
CREATE TABLE "playlists" (
	"id" serial PRIMARY KEY NOT NULL,
	"title" text NOT NULL,
	"description" text,
	"author_uid" text NOT NULL,
	"is_public" boolean DEFAULT false NOT NULL,
	"created_at" timestamp DEFAULT now()
);
--> statement-breakpoint
CREATE TABLE "tracks" (
	"id" serial PRIMARY KEY NOT NULL,
	"title" text NOT NULL,
	"artist" text NOT NULL,
	"album" text,
	"year" integer,
	"bpm" integer,
	"genre" text,
	"mood" jsonb,
	"duration_ms" integer,
	"explicit" boolean DEFAULT false,
	"time_of_day" jsonb,
	"isrc" text,
	"cover_url" text,
	"filename" text NOT NULL,
	"created_at" timestamp DEFAULT now()
);
--> statement-breakpoint
CREATE TABLE "usage_logs" (
	"id" serial PRIMARY KEY NOT NULL,
	"company_name" text,
	"track_id" integer,
	"track_title" text NOT NULL,
	"outlet_ip" text,
	"duration_played_second" integer,
	"played_at" timestamp DEFAULT now() NOT NULL
);
--> statement-breakpoint
CREATE TABLE "users" (
	"id" serial PRIMARY KEY NOT NULL,
	"uid" text NOT NULL,
	"email" text NOT NULL,
	"name" text,
	"role" text DEFAULT 'subscriber' NOT NULL,
	"pmpro_level" integer DEFAULT 1,
	"pin" text,
	"logo_url" text,
	"primary_color" text DEFAULT '#3b82f6',
	"app_name" text DEFAULT 'Background Music Player',
	"mfa_enabled" boolean DEFAULT false NOT NULL,
	"mfa_secret" text,
	"created_at" timestamp DEFAULT now(),
	CONSTRAINT "users_uid_unique" UNIQUE("uid")
);
--> statement-breakpoint
CREATE TABLE "vod_content" (
	"id" serial PRIMARY KEY NOT NULL,
	"title" text NOT NULL,
	"description" text,
	"filename" text NOT NULL,
	"thumbnail_url" text,
	"duration_ms" integer,
	"mime_type" text DEFAULT 'video/mp4' NOT NULL,
	"is_public" boolean DEFAULT false NOT NULL,
	"author_uid" text NOT NULL,
	"created_at" timestamp DEFAULT now()
);
--> statement-breakpoint
CREATE TABLE "wordpress_settings" (
	"id" serial PRIMARY KEY NOT NULL,
	"wp_url" text DEFAULT 'https://example.com/wp-json' NOT NULL,
	"app_username" text DEFAULT 'admin' NOT NULL,
	"app_password" text DEFAULT '' NOT NULL,
	"bidirectional" boolean DEFAULT true NOT NULL,
	"last_sync_time" timestamp
);
--> statement-breakpoint
CREATE TABLE "wordpress_sync_logs" (
	"id" serial PRIMARY KEY NOT NULL,
	"wp_id" integer,
	"wp_type" text NOT NULL,
	"title" text NOT NULL,
	"status" text DEFAULT 'synced' NOT NULL,
	"direction" text NOT NULL,
	"error_message" text,
	"sync_time" timestamp DEFAULT now() NOT NULL
);
--> statement-breakpoint
ALTER TABLE "contracts" ADD CONSTRAINT "contracts_license_id_licenses_id_fk" FOREIGN KEY ("license_id") REFERENCES "public"."licenses"("id") ON DELETE cascade ON UPDATE no action;--> statement-breakpoint
ALTER TABLE "invoices" ADD CONSTRAINT "invoices_user_id_users_id_fk" FOREIGN KEY ("user_id") REFERENCES "public"."users"("id") ON DELETE set null ON UPDATE no action;--> statement-breakpoint
ALTER TABLE "payments" ADD CONSTRAINT "payments_user_id_users_id_fk" FOREIGN KEY ("user_id") REFERENCES "public"."users"("id") ON DELETE set null ON UPDATE no action;--> statement-breakpoint
ALTER TABLE "payments" ADD CONSTRAINT "payments_license_id_licenses_id_fk" FOREIGN KEY ("license_id") REFERENCES "public"."licenses"("id") ON DELETE set null ON UPDATE no action;--> statement-breakpoint
ALTER TABLE "playlist_tracks" ADD CONSTRAINT "playlist_tracks_playlist_id_playlists_id_fk" FOREIGN KEY ("playlist_id") REFERENCES "public"."playlists"("id") ON DELETE cascade ON UPDATE no action;--> statement-breakpoint
ALTER TABLE "playlist_tracks" ADD CONSTRAINT "playlist_tracks_track_id_tracks_id_fk" FOREIGN KEY ("track_id") REFERENCES "public"."tracks"("id") ON DELETE cascade ON UPDATE no action;--> statement-breakpoint
ALTER TABLE "usage_logs" ADD CONSTRAINT "usage_logs_track_id_tracks_id_fk" FOREIGN KEY ("track_id") REFERENCES "public"."tracks"("id") ON DELETE set null ON UPDATE no action;--> statement-breakpoint
CREATE INDEX "payments_gateway_transaction_idx" ON "payments" USING btree ("gateway_transaction_id");--> statement-breakpoint
CREATE INDEX "playlists_title_idx" ON "playlists" USING btree ("title");--> statement-breakpoint
CREATE INDEX "tracks_title_idx" ON "tracks" USING btree ("title");--> statement-breakpoint
CREATE INDEX "tracks_artist_idx" ON "tracks" USING btree ("artist");--> statement-breakpoint
CREATE INDEX "tracks_genre_idx" ON "tracks" USING btree ("genre");