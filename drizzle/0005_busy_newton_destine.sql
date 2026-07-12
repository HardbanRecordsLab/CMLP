CREATE TABLE "coupons" (
	"id" serial PRIMARY KEY NOT NULL,
	"code" text NOT NULL,
	"discount_percent" integer,
	"discount_amount" integer,
	"max_uses" integer DEFAULT 1,
	"used_count" integer DEFAULT 0 NOT NULL,
	"min_amount" integer DEFAULT 0,
	"expires_at" timestamp,
	"is_active" boolean DEFAULT true NOT NULL,
	"created_at" timestamp DEFAULT now(),
	CONSTRAINT "coupons_code_unique" UNIQUE("code")
);
--> statement-breakpoint
ALTER TABLE "payments" ADD COLUMN "vat_rate" integer DEFAULT 23;--> statement-breakpoint
ALTER TABLE "payments" ADD COLUMN "coupon_code" text;--> statement-breakpoint
ALTER TABLE "users" ADD COLUMN "email_verified" boolean DEFAULT false NOT NULL;--> statement-breakpoint
CREATE INDEX "audit_logs_user_id_idx" ON "audit_logs" USING btree ("user_id");--> statement-breakpoint
CREATE INDEX "audit_logs_created_at_idx" ON "audit_logs" USING btree ("created_at");--> statement-breakpoint
CREATE INDEX "licenses_company_id_idx" ON "licenses" USING btree ("company_id");--> statement-breakpoint
CREATE INDEX "licenses_status_idx" ON "licenses" USING btree ("status");--> statement-breakpoint
CREATE INDEX "licenses_expires_at_idx" ON "licenses" USING btree ("expires_at");--> statement-breakpoint
CREATE INDEX "payments_user_id_idx" ON "payments" USING btree ("user_id");--> statement-breakpoint
CREATE INDEX "payments_status_idx" ON "payments" USING btree ("status");--> statement-breakpoint
CREATE INDEX "payments_license_id_idx" ON "payments" USING btree ("license_id");--> statement-breakpoint
CREATE INDEX "playlists_author_uid_idx" ON "playlists" USING btree ("author_uid");--> statement-breakpoint
CREATE INDEX "playlists_company_id_idx" ON "playlists" USING btree ("company_id");--> statement-breakpoint
CREATE INDEX "tracks_title_idx" ON "tracks" USING btree ("title");--> statement-breakpoint
CREATE INDEX "tracks_artist_idx" ON "tracks" USING btree ("artist");--> statement-breakpoint
CREATE INDEX "tracks_genre_idx" ON "tracks" USING btree ("genre");--> statement-breakpoint
CREATE INDEX "tracks_status_idx" ON "tracks" USING btree ("status");--> statement-breakpoint
CREATE INDEX "usage_logs_license_id_idx" ON "usage_logs" USING btree ("license_id");--> statement-breakpoint
CREATE INDEX "usage_logs_played_at_idx" ON "usage_logs" USING btree ("played_at");--> statement-breakpoint
CREATE INDEX "users_email_idx" ON "users" USING btree ("email");--> statement-breakpoint
ALTER TABLE "users" ADD CONSTRAINT "users_email_unique" UNIQUE("email");