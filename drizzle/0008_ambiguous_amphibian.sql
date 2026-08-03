CREATE TABLE "waitlist_signups" (
	"id" serial PRIMARY KEY NOT NULL,
	"email" text NOT NULL,
	"company_name" text,
	"message" text,
	"invited_at" timestamp,
	"created_at" timestamp DEFAULT now() NOT NULL,
	CONSTRAINT "waitlist_signups_email_unique" UNIQUE("email")
);
--> statement-breakpoint
CREATE INDEX "waitlist_signups_email_idx" ON "waitlist_signups" USING btree ("email");