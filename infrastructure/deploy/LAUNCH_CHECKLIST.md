# CMLP Production Launch Checklist

## Pre-Deployment Verification

### Code Quality
- [ ] All TypeScript errors resolved (`npm run build` passes)
- [ ] All tests passing (`npm test`)
- [ ] No critical vulnerabilities (`npm audit` clean)
- [ ] No hardcoded secrets in code
- [ ] All TODO/FIXME comments resolved
- [ ] Code formatted and linted

### Security
- [ ] All authentication endpoints protected
- [ ] All admin endpoints require `requireRole('admin')`
- [ ] JWT secrets are strong (64+ chars, randomly generated)
- [ ] HMAC secret is strong (32+ chars)
- [ ] Webhook secret is strong (32+ chars)
- [ ] CORS configured correctly (only allowed origins)
- [ ] Rate limiting enabled on all endpoints
- [ ] CSP headers configured (no `unsafe-eval`, `frame-ancestors 'self'`)
- [ ] SQL injection protection (parameterized queries)
- [ ] XSS protection (input sanitization, output escaping)
- [ ] CSRF protection (if applicable)
- [ ] File upload validation (size, type, path traversal)
- [ ] Password hashing (bcrypt, cost factor 10+)
- [ ] No sensitive data in logs
- [ ] Environment variables not in git

### Database
- [ ] PostgreSQL 16+ installed and running
- [ ] Database user created with strong password
- [ ] Database created with correct owner
- [ ] All migrations applied (`npm run db:migrate`)
- [ ] Indexes created (users.email, tracks.title, etc.)
- [ ] Foreign key constraints in place
- [ ] Backup strategy configured
- [ ] Connection pooling configured (if needed)

### Redis
- [ ] Redis 7+ installed and running
- [ ] Redis password configured
- [ ] Redis accessible from application
- [ ] Cache TTL configured appropriately
- [ ] Rate limiting working

### Storage
- [ ] Media directory created (`/opt/cmlp/media_files`)
- [ ] Correct permissions (cmlp:cmlp)
- [ ] Sufficient disk space
- [ ] Backup strategy for media files

### Environment Configuration
- [ ] `.env.production` created with all required variables
- [ ] All secrets generated with `openssl rand`
- [ ] No placeholder values remaining
- [ ] Database URL correct
- [ ] Redis URL correct
- [ ] Stripe keys configured (if using payments)
- [ ] WordPress credentials configured
- [ ] SMTP credentials configured
- [ ] Admin account configured

---

## Deployment

### VPS Preparation
- [ ] Ubuntu 22.04+ installed
- [ ] System updated (`apt update && apt upgrade`)
- [ ] Firewall configured (UFW: 22, 80, 443)
- [ ] SSH access secured (key-based auth)
- [ ] Application user created (`cmlp`)
- [ ] Application directory created (`/opt/cmlp`)

### Software Installation
- [ ] Node.js 20+ installed
- [ ] PM2 installed globally
- [ ] Nginx installed
- [ ] PostgreSQL 16+ installed
- [ ] Redis 7+ installed
- [ ] FFmpeg installed
- [ ] Git installed

### Application Deployment
- [ ] Repository cloned to `/opt/cmlp`
- [ ] Dependencies installed (`npm ci`)
- [ ] Application built (`npm run build`)
- [ ] Database migrations run
- [ ] PM2 process started
- [ ] PM2 startup script configured
- [ ] PM2 configuration saved

### Nginx Configuration
- [ ] SSL certificate obtained (Let's Encrypt)
- [ ] Nginx config created (`/etc/nginx/sites-available/cmlp-api`)
- [ ] Site enabled (symlink to `sites-enabled`)
- [ ] Nginx config tested (`nginx -t`)
- [ ] Nginx reloaded
- [ ] HTTP → HTTPS redirect working

### DNS Configuration
- [ ] `api.cmlp.hardbanrecordslab.online` → VPS IP
- [ ] `cmlp.hardbanrecordslab.online` → Vercel (or VPS)
- [ ] DNS propagated (check with `dig`)

---

## Post-Deployment Verification

### Health Checks
- [ ] `curl https://api.cmlp.hardbanrecordslab.online/api/health` returns `{"api":"ok","database":"ok","redis":"ok"}`
- [ ] Frontend loads: `https://cmlp.hardbanrecordslab.online`
- [ ] No console errors in browser
- [ ] No errors in PM2 logs (`pm2 logs`)
- [ ] No errors in Nginx logs (`/var/log/nginx/error.log`)

### Authentication
- [ ] Admin login works
- [ ] JWT tokens issued correctly
- [ ] Refresh tokens work
- [ ] MFA works (if enabled)
- [ ] Password reset works
- [ ] Email verification works

### API Endpoints
- [ ] `/api/tracks` - list tracks (requires auth)
- [ ] `/api/tracks` - upload track (admin only)
- [ ] `/api/playlists` - CRUD playlists
- [ ] `/api/licenses` - CRUD licenses
- [ ] `/api/payments` - payment flow
- [ ] `/api/audio/:filename` - streaming (with token)
- [ ] `/api/wordpress/settings` - WP sync (admin only)
- [ ] `/api/notifications/broadcast` - send notifications (admin only)
- [ ] `/api/strategic/vault/sign-certificate` - sign docs (admin only)

### File Operations
- [ ] Track upload works (MP3, WAV, FLAC)
- [ ] File size limit enforced (500MB)
- [ ] File type validation works
- [ ] HLS transcoding works (if enabled)
- [ ] Media files accessible via streaming endpoint
- [ ] X-Accel-Redirect working (production)

### Payments (if enabled)
- [ ] Stripe checkout session creation works
- [ ] Stripe webhook receives events
- [ ] Payment status updates correctly
- [ ] License activation on payment works
- [ ] Invoice generation works

### WordPress Integration
- [ ] WordPress settings configurable
- [ ] Bidirectional sync works
- [ ] Webhooks received from WordPress
- [ ] Sync logs visible

### Security Verification
- [ ] Unauthenticated requests rejected (401)
- [ ] Non-admin users cannot access admin endpoints (403)
- [ ] Rate limiting works (test with multiple requests)
- [ ] Invalid JWT rejected
- [ ] Expired JWT rejected
- [ ] CORS headers correct
- [ ] Security headers present (check with browser dev tools)

### Performance
- [ ] Response time < 500ms for API calls
- [ ] Static assets cached (Cache-Control headers)
- [ ] Gzip compression enabled
- [ ] Database queries optimized (check slow query log)
- [ ] Redis cache hit rate > 80%

### Monitoring
- [ ] PM2 monitoring active (`pm2 monit`)
- [ ] Logs rotating (PM2 log rotation)
- [ ] Error tracking configured (Sentry, if enabled)
- [ ] Uptime monitoring configured (UptimeRobot, etc.)
- [ ] Database backup scheduled (cron job)

---

## Final Checks

### Documentation
- [ ] README.md up to date
- [ ] API documentation current
- [ ] Deployment guide tested
- [ ] Rollback procedure documented
- [ ] Troubleshooting guide available

### Backup & Recovery
- [ ] Database backup script created
- [ ] Backup cron job configured (daily)
- [ ] Backup retention policy (30 days)
- [ ] Backup tested (restore to staging)
- [ ] Media files backup configured

### Rollback Plan
- [ ] Rollback script tested
- [ ] Previous version tagged in git
- [ ] Rollback procedure documented
- [ ] Team trained on rollback

### Go-Live Decision
- [ ] All critical checks passed
- [ ] Stakeholders notified
- [ ] Monitoring in place
- [ ] Support team ready
- [ ] Rollback plan ready

---

## Post-Launch Monitoring (First 24 Hours)

### Hour 1
- [ ] Monitor error rates
- [ ] Check response times
- [ ] Verify all endpoints working
- [ ] Monitor database connections
- [ ] Check Redis memory usage

### Hour 2-4
- [ ] Monitor user activity
- [ ] Check for unusual patterns
- [ ] Verify payment flow (if applicable)
- [ ] Monitor disk space
- [ ] Check log files for errors

### Hour 4-24
- [ ] Review error logs
- [ ] Check backup completed
- [ ] Verify cache performance
- [ ] Monitor CPU/RAM usage
- [ ] Check SSL certificate expiry

---

## Emergency Contacts

- **Server Admin:** [Name] - [Phone] - [Email]
- **Database Admin:** [Name] - [Phone] - [Email]
- **Developer:** [Name] - [Phone] - [Email]
- **Hosting Provider:** [Provider] - [Support Phone]

---

## Sign-Off

- [ ] **Developer:** All technical checks passed
- [ ] **QA:** All tests passed
- [ ] **Security:** Security audit passed
- [ ] **Ops:** Infrastructure ready
- [ ] **Management:** Go-live approved

**Launch Date:** _______________

**Approved By:** _______________

---

## Notes

Use this checklist to ensure all aspects are verified before going live. Each item should be checked off by the responsible person. Do not proceed to launch until all critical items are verified.

**Critical items (must pass):**
- All security checks
- All authentication checks
- Database migrations
- Health checks
- Backup configuration

**High priority items:**
- Performance checks
- Monitoring setup
- Documentation

**Medium priority items:**
- Optional features
- Nice-to-have optimizations
