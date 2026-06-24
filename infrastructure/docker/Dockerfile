# =========================================================
# Phase 11 Production Deployment: Docker Multi-Stage Build
# =========================================================

# --- Base Build Stage ---
FROM node:20-alpine AS base

WORKDIR /usr/src/app

ENV NODE_ENV=development

RUN apk add --no-cache python3 make g++

COPY package*.json ./

RUN rm -rf node_modules package-lock.json && npm install --omit=optional && npm install @rollup/rollup-linux-x64-musl --save-optional

COPY . .

RUN npm run build

RUN npm prune --production


# --- Migration Stage ---
FROM base AS migrate


# --- Production Runtime Stage ---
FROM node:20-alpine AS runner

WORKDIR /usr/src/app

ENV NODE_ENV=production
ENV PORT=3000

COPY --from=base /usr/src/app/package*.json ./
COPY --from=base /usr/src/app/node_modules ./node_modules
COPY --from=base /usr/src/app/dist ./dist
RUN mkdir -p /usr/src/app/media_files
COPY --from=base /usr/src/app/media_files ./media_files 2>/dev/null || true

RUN mkdir -p /usr/src/app/media_files && \
    chown -R node:node /usr/src/app

USER node

EXPOSE 3000

HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
  CMD node -e "fetch('http://localhost:3000/api/health').then(r => r.ok ? process.exit(0) : process.exit(1)).catch(() => process.exit(1))"

CMD ["node", "dist/server.cjs"]