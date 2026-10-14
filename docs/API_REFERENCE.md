# API Reference

The current API is implemented in `server.ts`. `src/api/*` contains feature-based router scaffolding for future extraction.

## Health

- `GET /api/health`

## Authentication

- `POST /api/outlet/login`
- `POST /api/auth/register-sync`
- `GET /api/auth/mfa/status`
- `POST /api/auth/mfa/validate`
- `POST /api/auth/mfa/setup`
- `POST /api/auth/mfa/confirm`
- `POST /api/auth/mfa/disable`

## Security And GDPR

- `GET /api/security/blocklist`
- `POST /api/security/blocklist/block`
- `POST /api/security/blocklist/unblock`
- `POST /api/security/owasp-scan`
- `GET /api/gdpr/export`
- `POST /api/gdpr/delete`
- `POST /api/gdpr/consent`

## Admin, Reporting, And Users

- `GET /api/reports/usage`
- `GET /api/reports/financials`
- `GET /api/reports/compliance`
- `GET /api/audit-logs`
- `GET /api/stats`
- `GET /api/users`
- `POST /api/users`

## Media, Playlists, And Streaming

- `GET /api/tracks`
- `GET /api/tracks/public`
- `POST /api/tracks`
- `GET /api/playlists`
- `POST /api/playlists`
- `GET /api/playlists/:id`
- `PUT /api/playlists/:id`
- `DELETE /api/playlists/:id`
- `POST /api/playlists/:id/tracks`
- `DELETE /api/playlists/:id/tracks/:trackId`
- `GET /api/vod`
- `POST /api/vod`
- `DELETE /api/vod/:id`
- `GET /api/audio/token/:filename`
- `GET /api/audio/:filename`
- `POST /api/telemetry/playback`

## Licensing And Payments

- `GET /api/licenses`
- `POST /api/licenses`
- `GET /api/licenses/:id/contract`
- `GET /api/licenses/:id/pdf`
- `POST /api/licenses/:id/sign`
- `POST /api/licenses/:id/renew`
- `POST /api/licenses/:id/cancel`
- `GET /api/payments`
- `POST /api/payments/checkout-session`
- `GET /api/payments/simulate-success`
- `POST /api/payments/:id/refund`
- `POST /api/payments/webhook/:gateway`

## WordPress And Notifications

- `GET /api/wordpress/settings`
- `POST /api/wordpress/settings`
- `GET /api/wordpress/logs`
- `POST /api/wordpress/sync`
- `POST /api/wordpress/webhook`
- `GET /api/notifications/settings`
- `POST /api/notifications/settings`
- `GET /api/notifications/logs`
- `POST /api/notifications/broadcast`
- `POST /api/notifications/test-email`

## Strategic Services

- `GET /api/strategic/waveform/:trackId`
- `POST /api/strategic/licensing/predictive-checks`
- `POST /api/strategic/vault/sign-certificate`
