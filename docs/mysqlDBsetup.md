# MySQL Database Setup

## ON mysql as ROOT:

1. Create database called ProjectDB
```
create database ProjectDB;
```

2. Create admin user
```
create user 'admin'@'localhost' identified by 'adminPass';
```

3. Granting admin user priviledges to the ProjectDB 
```
grant all privileges on ProjectDB.* to 'admin'@'localhost';
```

## ON mysql as ADMIN:
1. Login to admin for MySQL. The password is `adminPass`.  
```
mysql -u admin -p ProjectDB
```

2. Create Users table
```
create table if not exists Users(
    id int not null auto_increment,
    username varchar(20) not null,
    email varchar(100) not null,
    passHash varchar(60) not null,
    primary key(id),
    unique (email),
    unique (username)
);
```
OR

```
create table if not exists Users(id int not null auto_increment, username varchar(20) not null, email varchar(100) not null, passHash varchar(60) not null, primary key(id), unique (email), unique (username));
```

select user, host, authentication_string from mysql.user; //NOT NEEDED

