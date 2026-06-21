#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")/../.."

if [ ! -f infrastructure/environment/.env.production ]; then
  echo "Missing infrastructure/environment/.env.production. Copy infrastructure/environment/.env.vps.example and fill real secrets first."
  exit 1
fi

docker compose -f infrastructure/docker/docker-compose.yml up -d --build
docker compose -f infrastructure/docker/docker-compose.yml logs -f cmlp-app
