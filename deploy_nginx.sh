#!/bin/bash
set -e

NGINX_AVAILABLE="/etc/nginx/sites-available"
NGINX_ENABLED="/etc/nginx/sites-enabled"
VPS_NGINX="/etc/nginx/sites-available"

echo "=== Deploying CMLP nginx configs ==="

# Copy configs to VPS
for domain in vault status docs auto cmlp; do
    scp -o StrictHostKeyChecking=no \
        "G:/CMLP HardbanRecordsLab/etc/nginx/sites-available/${domain}.hardbanrecordslab.online.conf" \
        root@84.247.162.167:/etc/nginx/sites-available/
done

# Copy api cmlp config
scp -o StrictHostKeyChecking=no \
    "G:/CMLP HardbanRecordsLab/etc/nginx/sites-available/api.cmlp.hardbanrecordslab.online.conf" \
    root@84.247.162.167:/etc/nginx/sites-available/

# Enable all sites
ssh -o StrictHostKeyChecking=no root@84.247.162.167 "
    ln -sf /etc/nginx/sites-available/cmlp.hardbanrecordslab.online.conf /etc/nginx/sites-enabled/
    ln -sf /etc/nginx/sites-available/api.cmlp.hardbanrecordslab.online.conf /etc/nginx/sites-enabled/
    
    # Update existing hardbanrecordslab.conf to add admin subdomain
    sed -i 's/server_name hardbanrecordslab.online www.hardbanrecordslab.online;/server_name hardbanrecordslab.online www.hardbanrecordslab.online admin.hardbanrecordslab.online;/' /etc/nginx/sites-enabled/hardbanrecordslab.conf
    sed -i 's/server_name hardbanrecordslab.online www.hardbanrecordslab.online;/server_name hardbanrecordslab.online www.hardbanrecordslab.online admin.hardbanrecordslab.online;/' /etc/nginx/sites-available/hardbanrecordslab.conf
    
    # Test nginx config
    nginx -t
"

echo "=== Nginx config deployed ==="