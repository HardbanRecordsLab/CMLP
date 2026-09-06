#!/bin/bash
# =========================================================
# CMLP — deploy motywu WordPress na VPS 84.247.162.167
# Kontener `main-website-wordpress-1`, motyw w /var/www/html/wp-content/themes/.
# Produkcją jest motyw POTOMNY (Template: hrl-theme). Skrypt wykrywa aktywny
# motyw i kopiuje do niego zawartość child-theme + assety CMLP; parent
# `hrl-theme` aktualizuje osobno (fallback + szablony nienadpisane).
#
# Uruchom z maszyny dev:  VPS_HOST=root@84.247.162.167 ./vps-deploy/deploy-theme.sh
# =========================================================
set -euo pipefail

VPS_HOST="${VPS_HOST:-root@84.247.162.167}"
SSH_KEY="${SSH_KEY:-$HOME/.ssh/vps_key}"
REPO_DIR="$(cd "$(dirname "$0")/.." && pwd)"
WP_CT="main-website-wordpress-1"

echo "=== Deploy motywu WordPress → $VPS_HOST ($WP_CT) ==="

# 1. spakuj obie warstwy motywu z repo
TMP="$(mktemp -d)"
cp -a "$REPO_DIR/wordpress/." "$TMP/parent/"
rm -rf "$TMP/parent/hrl-child-theme-patch" "$TMP/parent/wordpress.zip" "$TMP/parent/"*.zip 2>/dev/null || true
cp -a "$REPO_DIR/wordpress/hrl-child-theme-patch/child-theme/." "$TMP/child/"
tar czf "$TMP/theme-deploy.tgz" -C "$TMP" parent child

# 2. wyślij i rozpakuj na serwerze
scp -i "$SSH_KEY" "$TMP/theme-deploy.tgz" "$VPS_HOST:/tmp/cmlp-theme-deploy.tgz"
rm -rf "$TMP"

ssh -i "$SSH_KEY" "$VPS_HOST" WP_CT="$WP_CT" 'bash -s' <<'REMOTE'
set -euo pipefail
CT="$WP_CT"
rm -rf /tmp/cmlp-theme && mkdir -p /tmp/cmlp-theme
tar xzf /tmp/cmlp-theme-deploy.tgz -C /tmp/cmlp-theme

THEMES=/var/www/html/wp-content/themes
BK="/root/decommissioned/themes-$(date +%F-%H%M)"
docker exec "$CT" sh -lc "cd $THEMES && tar czf - . " > "$BK.tgz" && echo "→ backup motywów: $BK.tgz"

ACTIVE=$(docker exec "$CT" wp --allow-root theme list --status=active --field=name 2>/dev/null | head -1)
echo "→ aktywny motyw: ${ACTIVE:-?}"

echo "→ aktualizacja parent hrl-theme"
docker cp /tmp/cmlp-theme/parent/. "$CT:$THEMES/hrl-theme/"

if [ -n "$ACTIVE" ] && [ "$ACTIVE" != "hrl-theme" ]; then
  echo "→ aktualizacja child $ACTIVE"
  docker cp /tmp/cmlp-theme/child/. "$CT:$THEMES/$ACTIVE/"
else
  echo "!! aktywny motyw = hrl-theme lub nieznany — child-theme scal ręcznie ($THEMES/$ACTIVE)"
fi

docker exec "$CT" chown -R www-data:www-data "$THEMES"
docker exec "$CT" wp --allow-root cache flush 2>/dev/null || true
docker exec "$CT" wp --allow-root rewrite flush 2>/dev/null || true
docker restart "$CT"

sleep 5
curl -sf -o /dev/null -w 'portal / → %{http_code}\n' https://hardbanrecordslab.online/ || true
curl -sf -o /dev/null -w '/cmlp/  → %{http_code}\n' https://hardbanrecordslab.online/cmlp/ || true
REMOTE

echo
echo "=== zrobione. Po wgraniu: WP → Wygląd → Dostosuj → Ikona witryny (favicon-512.png) ==="
echo "=== Rollback: docker exec $WP_CT sh -lc 'cd /var/www/html/wp-content/themes && tar xzf -' < /root/decommissioned/themes-*.tgz ; docker restart $WP_CT ==="
