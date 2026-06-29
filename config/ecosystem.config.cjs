// =========================================================
// Phase 11 Production Deployment: PM2 Cluster Configuration
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
        SQL_HOST: "localhost",
        SQL_PORT: "5432",
        SQL_USER: "hbrl_admin",
        SQL_PASSWORD: "HardbanRecordsLab2026",
        SQL_DB_NAME: "hbrl_central",
        REDIS_URL: "redis://localhost:6379/0",
        HMAC_SECRET: "hrl_hmac_stream_signing_key_2026_change_me",
        JWT_SECRET: "hrl_jwt_production_secret_2026_change_me"
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
