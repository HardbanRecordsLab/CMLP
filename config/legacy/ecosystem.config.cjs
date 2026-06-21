// =========================================================
// Phase 11 Production Deployment: PM2 Cluster Configuration
// =========================================================

module.exports = {
  apps: [
    {
      name: "hrl-licensing-platform",
      script: "./dist/server.cjs",
      instances: "max",       // Leverages multi-core concurrency bounds
      exec_mode: "cluster",   // Cluster mode for zero-downtime rolling reloads
      watch: false,           // HMR disabled in production runtime
      env: {
        NODE_ENV: "production",
        PORT: 3000
      },
      // Performance and memory ceiling thresholds
      max_memory_restart: "600M",
      restart_delay: 3000,
      max_restarts: 10,
      
      // Fine-grained tracking logs
      error_file: "./logs/pm2_err.log",
      out_file: "./logs/pm2_out.log",
      log_date_format: "YYYY-MM-DD HH:mm:ss Z",
      merge_logs: true,
      
      // Auto-tuning connection management
      listen_timeout: 8000,
      kill_timeout: 5000
    }
  ]
};
