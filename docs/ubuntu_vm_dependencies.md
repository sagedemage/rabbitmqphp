# Ubuntu VM Dependencies
Here are the dependencies that are required to run the Front-end VM and Back-end VM.

## Front-end VM Dependencies
1. apache2
2. php
3. php-common
4. php-amqp

The command to run to install the **front-end VM dependencies**.
```
sudo apt install apache2 php php-common php-amqp
```
## Back-end VM Dependencies
1. mysql-common
2. mysql-server
3. php
4. php-common
5. php-amqp
6. php-mysql
7. rabbitmq-server

The command to run to install the **back-end VM dependencies**.
```
sudo apt install mysql-common mysql-server php php-common php-amqp php-mysql rabbitmq-server
```

## Development Dependencies
1. vim
2. git
3. ssh

The command to run to install the **development dependencies**.
```
sudo apt install vim git ssh
```

## 2FA Dependencies
1. PHP Mailer

The command to run to install the **2FA Dependencies**
```
sudo apt install libphp-phpmailer
```
