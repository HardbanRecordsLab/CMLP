CREATE INDEX "companies_owner_id_idx" ON "companies" USING btree ("owner_id");--> statement-breakpoint
CREATE INDEX "licenses_author_uid_idx" ON "licenses" USING btree ("author_uid");