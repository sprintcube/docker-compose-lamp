# LAMP Stack on Docker with NODE/NPM

This repo is a Docker LAMP environment for **local** development. You can set up multiple local website and you can easily configure the version of PHP, MySQL or Node.

This Docker environment gives you the basic tools you need for the PHP and frontend development:
* PHP
* Apache
* MySQL/MariaDB
* phpMyAdmin
* Redis
* Node / NPM
* Composer

**Note for M1 Mac**: at the time of this writing there is no official image of MySQL for M1 chip; please use MariaDB instead.

**Credit:** This repo is a fork of the awesome repo https://github.com/sprintcube/docker-compose-lamp. This fork adds the support for NODE/NPM and examples.



---
##  Installation

#### 1. Clone this repository on your local computer.
```shell
git clone https://github.com/danielefavi/lamp-docker.git
```

#### 2. Duplicate the file `.env.example` and rename it into `.env`.
```shell
cd lamp-docker
cp .env.example .env
```

#### 3. configure your `.env`.

Open the `.env` file just duplicated and choose your PHP version, database version and default Node version.

#### 4. Start the container.

For Linux/Mac users:
```shell
sudo docker-compose up -d
```

For Windows users:
Open the terminal with administration rights and execute the command below:
```shell
docker-compose up -d
```

Your LAMP stack is now ready! You can access it via [http://localhost](http://localhost)

---
## How to configure a local virtual host

1. Add the local host in your hosts:
On Linux/Mac the file is `/etc/hosts`. On Windows the host file is `C:\Windows\System32\Drivers\etc\hosts`.
```
127.0.0.1   website.local
```

2. Add the virtual host entry in the file `vhosts/default.conf`. In the file `vhosts/default.conf` you can find sone examples for Laravel, Wordpress or a plain website.

3. Restart the container.



---
## Laravel and Wordpress database setting examples

Note that the password of the `root` user shown below is set in the `.env` file.

### Laravel .ENV example

```
DB_CONNECTION=mysql
DB_HOST=database
DB_PORT=3306
DB_DATABASE=YOUR_DATABASE_HERE
DB_USERNAME=root
DB_PASSWORD=tiger
```

### Wordpress wp-config example

```php
/** The name of the database for WordPress */
define( 'DB_NAME', 'YOUR_DATABASE_HERE' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'tiger' );

/** MySQL hostname */
define( 'DB_HOST', 'database' );
```


---
## Useful commands

Note that the `docker-compose` command must be executed in the `lamp-docker` folder where the YMLs files resides.

## Node and NPM commands

You can choose different version of node: `node17`, `node16`, `node14` and `node12`. You can use just `node` for the default version set in the `.env`.


For example, to use the node 17 version you can execute the following command:
```shell
docker-compose -f docker-compose.node.yml run node14 bash
cd project-folder
npm install
```

Below another example for running an NPM command using the default node version (you can set it in the `.env`):
```shell
docker-compose -f docker-compose.node.yml run node bash -c "cd laravel-app && npm run watch"
```


---
## Composer commands

First enter in `webserver` container (you can find the name of the webserver service running the command `docker-compose ps` in the *lamp-docker* folder):

```shell
docker-compose run webserver bash
```

Then execute the composer command.

```shell
mkdir example-app
cd example-app
composer create-project laravel/laravel .
```


---
## Other commands

```shell
docker-compose run SERVICE_NAME bash
```

```shell
docker-compose ps
```

```shell
docker-compose run webserver bash
```

```shell
docker ps
```

```shell
docker exec -it CONTAINER_NAME /bin/sh
```


---
##  Configuration and Usage

### General Information
This Docker Stack is build for local development and not for production usage.

### Configuration
This package comes with default configuration options. You can modify them by creating `.env` file in your root directory.
To make it easy, just copy the content from `sample.env` file and update the environment variable values as per your need.

### Configuration Variables
There are following configuration variables available and you can customize them by overwritting in your own `.env` file.

---
#### PHP
---
_**PHPVERSION**_
Is used to specify which PHP Version you want to use. Defaults always to latest PHP Version.

_**PHP_INI**_
Define your custom `php.ini` modification to meet your requirments.

---
#### Apache
---

_**DOCUMENT_ROOT**_

It is a document root for Apache server. The default value for this is `./www`. All your sites will go here and will be synced automatically.

_**APACHE_DOCUMENT_ROOT**_

Apache config file value. The default value for this is /var/www/html.

_**VHOSTS_DIR**_

This is for virtual hosts. The default value for this is `./config/vhosts`. You can place your virtual hosts conf files here.

> Make sure you add an entry to your system's `hosts` file for each virtual host.

_**APACHE_LOG_DIR**_

This will be used to store Apache logs. The default value for this is `./logs/apache2`.

---
#### Database
---

_**DATABASE**_
Define which MySQL or MariaDB Version you would like to use.

_**MYSQL_DATA_DIR**_

This is MySQL data directory. The default value for this is `./data/mysql`. All your MySQL data files will be stored here.

_**MYSQL_LOG_DIR**_

This will be used to store Apache logs. The default value for this is `./logs/mysql`.

## Web Server

Apache is configured to run on port 80. So, you can access it via `http://localhost`.

#### Apache Modules

By default following modules are enabled.

* rewrite
* headers

> If you want to enable more modules, just update `./bin/phpX/Dockerfile`. You can also generate a PR and we will merge if seems good for general purpose.
> You have to rebuild the docker image by running `docker-compose build` and restart the docker containers.

#### Connect via SSH

You can connect to web server using `docker-compose exec` command to perform various operation on it. Use below command to login to container via ssh.

```shell
docker-compose exec webserver bash
```

## PHP

The installed version of php depends on your `.env`file.

#### Extensions

By default following extensions are installed.
May differ for PHP Verions <7.x.x

* mysqli
* pdo_sqlite
* pdo_mysql
* mbstring
* zip
* intl
* mcrypt
* curl
* json
* iconv
* xml
* xmlrpc
* gd

> If you want to install more extension, just update `./bin/webserver/Dockerfile`. You can also generate a PR and we will merge if it seems good for general purpose.
> You have to rebuild the docker image by running `docker-compose build` and restart the docker containers.

## phpMyAdmin

phpMyAdmin is configured to run on port 8080. Use following default credentials.

http://localhost:8080/
username: root
password: tiger

## Redis

It comes with Redis. It runs on default port `6379`.
