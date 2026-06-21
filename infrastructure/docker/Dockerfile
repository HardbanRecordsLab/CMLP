# =========================================================
# Phase 11 Production Deployment: Docker Multi-Stage Build
# =========================================================

# --- Base Build Stage ---
FROM node:20-alpine AS base

WORKDIR /usr/src/app

# Set node environment
ENV NODE_ENV=production

# Install essential dev-tools for building native extensions if any
RUN apk add --no-cache python3 make g++

# Copy package descriptors
COPY package*.json ./

# Install dependencies (both dev and prod so compilation runs)
RUN npm ci --include=dev

# Copy source tree and configuration files
COPY . .

# Build application static assets and bundle server code
RUN npm run build


# --- App Runtime Build Stage ---
FROM base AS builder

# Prune unnecessary development modules for runtime size efficiency
RUN npm prune --production


# --- Migration Stage ---
FROM base AS migrate


# --- Production Runtime Stage ---
FROM node:20-alpine AS runner

WORKDIR /usr/src/app

ENV NODE_ENV=production
ENV PORT=3000

# Copy assets and server configurations compiled in builder
COPY --from=builder /usr/src/app/package*.json ./
COPY --from=builder /usr/src/app/node_modules ./node_modules
COPY --from=builder /usr/src/app/dist ./dist
COPY --from=builder /usr/src/app/media_files ./media_files

# Create directories and adjust permissions for non-root runtime environments
RUN mkdir -p /usr/src/app/media_files && \
    chown -R node:node /usr/src/app

# Drop root privileges for security
USER node

EXPOSE 3000

# Health check setup
HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
  CMD node -e "fetch('http://localhost:3000/api/health').then(r => r.ok ? process.exit(0) : process.exit(1)).catch(() => process.exit(1))"

# Start the bundled server CJS
CMD ["node", "dist/server.cjs"]
