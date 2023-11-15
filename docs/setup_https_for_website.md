# Setup HTTPS for the Website

## Enable HTTPS for the Website
1. Enable Mod SSL
    ```
    sudo a2enmod ssl
    ```

2. Restart apache server
    ```
    sudo systemctl restart apache2
    ```

3. Generate self-signed SSL certificate
    ```
    sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/ssl/private/www.electrik.com.key -out /etc/ssl/certs/www.electrik.com.crt
    ```

4. Separate virtual host file
    ```
    sudo cp /etc/apache2/sites-available/002-electrik.com.conf /etc/apache2/sites-available/002-electrik-ssl.conf
    ```

5. Add apache ssl configuration to /etc/apache2/sites-available directory
    ```
    sudo cp electrik/002-electrik-ssl.conf /etc/apache2/sites-available/
    ```

6. Enable SSL version of the website
    ```
    cd /etc/apache2/sites-available/
    sudo a2ensite 002-electrik-ssl.conf
    ```

7. Clear DNS cache
    ```
    resolvectl flush-caches
    ```

8. Stop and start the apache server
    ```
    sudo systemctl stop apache2
    sudo systemctl start apache2
    ```


