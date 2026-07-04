// =========================================================
// CMLP Production PM2 Cluster Configuration
// Matches VPS: PostgreSQL on port 5433, hbrl_master DB
// =========================================================

module.exports = {
  apps: [
    {
      name: "hrl-licensing-platform",
      script: "./dist/server.cjs",
      cwd: "/opt/cmlp",
      instances: "max",
      exec_mode: "cluster",
      watch: false,
      env: {
        NODE_ENV: "production",
        PORT: 3000,
        // PostgreSQL (Docker: cmlp_service_db on port 5433)
        SQL_HOST: "localhost",
        SQL_PORT: "5433",
        SQL_USER: "hbrl_admin",
        SQL_PASSWORD: "HardbanRecordsLab2026",
        SQL_DB_NAME: "hbrl_master",
        // Redis (Docker: cmlp_service_redis on port 6379)
        REDIS_HOST: "127.0.0.1",
        REDIS_PORT: "6379",
        REDIS_URL: "redis://127.0.0.1:6379/0",
        // Security
        HMAC_SECRET: "hrl_hmac_stream_signing_key_2026_change_me",
        JWT_SECRET: "hrl_jwt_production_secret_2026_change_me",
        REFRESH_SECRET: "hrl_refresh_production_secret_2026_change_me",
        // Stripe (set via .env or VPS env vars)
        STRIPE_SECRET_KEY: "",
        STRIPE_WEBHOOK_SECRET: "",
        // AI
        GEMINI_API_KEY: "",
        // WordPress
        WP_URL: "https://hardbanrecordslab.online",
        WP_APP_USERNAME: "cmlp-sync-bot",
        WP_APP_PASSWORD: ""
      },
      max_memory_restart: "600M",
      restart_delay: 3000,
      max_restarts: 10,
      error_file: "/opt/cmlp/logs/pm2_err.log",
      out_file: "/opt/cmlp/logs/pm2_out.log",
      log_date_format: "YYYY-MM-DD HH:mm:ss Z",
      merge_logs: true,
      listen_timeout: 8000,
      kill_timeout: 5000
    }
  ]
};