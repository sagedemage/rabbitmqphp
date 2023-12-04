#!/bin/sh
# Enable HTTPS for the Website

# Enable Mod SSL
sudo a2enmod ssl

# Restart apache server
sudo systemctl restart apache2

# Generate self-signed SSL certificate
sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/ssl/private/www.electrik.com.key -out /etc/ssl/certs/www.electrik.com.crt

# Add apache ssl configuration to /etc/apache2/sites-available directory
sudo cp electrik/002-electrik-ssl.conf /etc/apache2/sites-available/

# Enable SSL version of the website
cd /etc/apache2/sites-available/
sudo a2ensite 002-electrik-ssl.conf

# Clear DNS cache
resolvectl flush-caches

# Stop and start the apache server
sudo systemctl stop apache2
sudo systemctl start apache2


