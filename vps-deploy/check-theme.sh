#!/bin/bash
set -e

echo "=== Checking WordPress theme files ==="

# Check if academy page is missing
if [ ! -f /var/www/html/wp-content/themes/hrl-theme/page-academy.php ]; then
  echo "MISSING: page-academy.php"
fi

# Check PHP syntax of key files
for f in functions.php header.php footer.php front-page.php page-muzyczna-kreacja-slow.php; do
  if [ -f "/var/www/html/wp-content/themes/hrl-theme/$f" ]; then
    php -l "/var/www/html/wp-content/themes/hrl-theme/$f" 2>&1 | grep -E "Errors parsing|Parse error|No syntax errors" || true
  fi
done

echo "=== Checking WordPress debug log ==="
tail -n 50 /var/www/html/wp-content/debug.log 2>/dev/null || echo "No debug log found"
