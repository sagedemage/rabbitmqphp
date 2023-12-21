#!/bin/bash

# Replace this with your actual PHP script path
PHP_SCRIPT_PATH="~/rabbitmqphp/db_listener/dbListener.php"

# Service file content
SERVICE_FILE_CONTENT="[Unit]
Description=My PHP Service

[Service]
ExecStart=/usr/bin/php $PHP_SCRIPT_PATH
Restart=always

[Install]
WantedBy=multi-user.target"

# Creating systemd service file
SERVICE_FILE="/etc/systemd/system/electrik.service"
echo "$SERVICE_FILE_CONTENT" | sudo tee $SERVICE_FILE

# Reloading systemd daemon and enabling service
sudo systemctl daemon-reload
sudo systemctl enable electrik.service

# Starting the service
sudo systemctl start electrik.service

# Displaying the status of the service
echo "Service status:"
sudo systemctl status electrik.service
