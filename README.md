# STL-1

## Requirements
- PHP >= 7.3.12
- symfony >= 4.4.x

## Installation 
Symfony utilizes composer to manage its dependencies. So, before using symfony, make sure you have composer installed on your machine. To download all required packages run a following commands or you can download [Composer](https://getcomposer.org/doc/00-intro.md).
- composer install `OR` COMPOSER_MEMORY_LIMIT=-1 composer install

## Database Setup
Need to set a .env file to make database connection with this setting.
```
DATABASE_URL=mysql://username:password@host:port/database_name
```

Use following commands to create and run a migration file for database scheme.
- php bin/console make:migration
- php bin/console doctrine:migrations:migrate 

`OR`

Open [https://adminer.solutions.smfhq.com/](https://adminer.solutions.smfhq.com/) for database dump and restore.

## Run
Use below command to start the project.
```
symfony server:start 
OR 
php -S 127.0.0.1:8000 -t public public/index.php
OR
php bin/console server:run
```

## Docker Environment
A Docker container is also available for development. Make sure you have installed docker on your machine before using docker. This Docker container launches an Apache server and serves this application with MariaDB. Now, time to make and run docker process as follows.

#### Requirements
- .docker/docker-compose.yml use to create docker images and containers
- .docker/Dockerfile to handle php:7.3-apache configuration and setting
- .docker/vhost.conf use for application virtual host

#### Database Setting
- A MariaDB 10.4.13 is running and available at mysql://app:password@universalpos-mariadb:3306/universalpos from within Docker assuming there is an external link to this container.

- These docker-compose.yml variables can be replaced to link different database.
```
DATABASE_NAME=universalpos
DATABASE_USER=app
DATABASE_PASSWORD=password
DATABASE_ROOT_PASSWORD=password
```
- Database port can also be replaced in docker-compose.yml under the ports.
```
ports:
    - 13306:3306
```
From the Docker running machine, you can connect it via 
```
mysql://app:password@localhost:13306/universalpos
```

#### Build And Run Docker
The application needs to be build on the first launch like this (adjust path to your needs):
```bash
docker-compose -f .docker/docker-compose.yml up --build
```
Afterwards, it can just be launched:
```bash
docker-compose -f .docker/docker-compose.yml up
```
This Docker container launches an Apache server and serves this application. It is reachable under http://localhost:8080.

Docker images can also be login for testing, installation and database process with this.
```
docker-compose -f .docker/docker-compose.yml exec SERVICENAME sh
```
