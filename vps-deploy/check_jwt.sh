#!/bin/bash
PIDS=$(ps aux | grep 'node /opt/cmlp/dist/server.cjs' | grep -v grep | awk '{print $2}')
for PID in $PIDS; do
  echo "=== PID $PID ==="
  cat /proc/$PID/environ 2>/dev/null | tr '\0' '\n' | grep -i jwt
done
