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
        SQL_HOST: "localhost",
        SQL_PORT: "5433",
        REDIS_HOST: "127.0.0.1",
        REDIS_PORT: "6379",
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