# Setup Web Server

1. Append `127.0.0.1 www.electrik.com` in */etc/hosts*
    ```
    ...
    127.0.0.1 www.electrik.com
    ...
    ```

2. Go to the *rabbimqphp* repository
    ```
    cd ~/git/rabbitmqphp
    ```

3. Copy **electrik** folder to */var/www*
    ```
    sudo cp -r electrik/ /var/www/
    ``` 

4. Copy **002-electrik.conf** file to */etc/apache2/sites-available/*
    ```
    sudo cp electrik/002-electrik.conf /etc/apache2/sites-available/
    ``` 

5. Go to */etc/apache2/sites-enabled*
    ```
    cd /etc/apache2/sites-enabled
    ```

6. Create **002-electrik.conf** symlink in */etc/apache2/sites-enabled*
    ```
    sudo ln -s ../sites-available/002-electrik.conf 002-electrik.conf
    ```

7. Clear DNS cache
    ```
    resolvectl flush-caches
    ```

8. You should be able to access the website on [http://www.electrik.com/home.html](http://www.electrik.com/home.html)