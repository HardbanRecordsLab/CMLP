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
        PORT: 3000
      },
      max_memory_restart: "600M",
      restart_delay: 3000,
      max_restarts: 10,
      error_file: "./logs/pm2_err.log",
      out_file: "./logs/pm2_out.log",
      log_date_format: "YYYY-MM-DD HH:mm:ss Z",
      merge_logs: true,
      listen_timeout: 8000,
      kill_timeout: 5000
    }
  ]
};
