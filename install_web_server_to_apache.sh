#!/bin/sh
sudo mkdir -v /var/www/electrik
sudo cp -rv electrik/*.html /var/www/electrik/
sudo cp -rv electrik/*.php /var/www/electrik/
sudo cp -rv electrik/*.css /var/www/electrik/
sudo cp -rv electrik/*.js /var/www/electrik/
sudo cp -rv rabbitmq_lib /var/www/
