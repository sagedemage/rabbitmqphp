# Ubuntu VM Dependencies
Here are the dependencies that are required to run the web server and to do development on the Ubuntu VM.

## Web Server Dependencies
1. apache2
2. mysql-common
3. mysql-server
4. php
5. php-common
6. php-amqp
7. php-mysql
8. rabbitmq-server

Command to run to install the web server dependencies
```
sudo apt install apache2 mysql-common mysql-server php php-common php-amqp php-mysql rabbitmq-server
```

## Development Dependencies
1. vim
2. git
3. ssh

Command to install the development dependencies
```
sudo apt install vim git ssh
```