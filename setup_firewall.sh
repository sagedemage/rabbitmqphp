#!/bin/bash

# Enable UFW
sudo ufw enable

# Set default policies to deny all incoming traffic, allow all outgoing
sudo ufw default deny incoming
sudo ufw default allow outgoing

# Allow specific ports
sudo ufw allow 80/tcp    # For HTTP
sudo ufw allow 443/tcp   # For HTTPS
sudo ufw allow 53        # For DNS
sudo ufw allow 22/tcp    # For SSH
sudo ufw allow 25/tcp    # For SMTP
sudo ufw allow 3306      # For mySQL
sudo ufw allow 5672/tcp  # For AMQP (rabbitmq)
sudo ufw allow 5671/tcp  # For AMQP over TLS/SSL (rabbitmq)
sudo ufw allow 15672/tcp # For AMQP (rabbitmq ui)
sudo ufw allow 9993      # For ZeroTier

# Reload UFW to apply changes
sudo ufw reload

# Optional: Enable logging for denied packets
sudo ufw logging on

#Display firewall config (verbose)
sudo ufw status verbose
