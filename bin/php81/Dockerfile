FROM php:8.1.12-apache-buster

# Surpresses debconf complaints of trying to install apt packages interactively
# https://github.com/moby/moby/issues/4032#issuecomment-192327844

ARG DEBIAN_FRONTEND=noninteractive

# Update & upgrade & autoremove
RUN apt-get update -y --fix-missing && \
	apt-get upgrade -y --fix-missing && \
	apt-get autoremove -y --fix-missing

# Install useful tools and install important libaries
RUN apt-get install -y --no-install-recommends --fix-missing apt-utils nano wget dialog librdkafka-dev libsqlite3-dev libsodium-dev libsqlite3-0 \
    default-mysql-client zlib1g-dev libzip-dev libpq-dev libicu-dev build-essential git curl libonig-dev unzip libcurl4 ca-certificates gnupg \
	libcurl4-openssl-dev zip golang-go openssl libc-client-dev libkrb5-dev libmagickwand-dev libldap2-dev libnss-ldap libpam-ldap ldap-utils
    
# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install XDebug
RUN pecl install xdebug-3.2.0 && \
    docker-php-ext-enable xdebug

# Install Redis
RUN pecl install redis-5.3.3 && \
    docker-php-ext-enable redis

# Imagick to install
# https://github.com/Imagick/imagick
RUN cd /usr/local/src && \
    git clone https://github.com/Imagick/imagick && \
    cd imagick && \
    phpize && \
    ./configure && \
    make && \
    make install && \
    cd .. && \
    rm -rf imagick && \
    docker-php-ext-enable imagick

# Other PHP81 Extensions
RUN docker-php-ext-install pdo_mysql && \
    docker-php-ext-install pdo_sqlite && \
    docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && \
    docker-php-ext-install pdo pdo_pgsql pgsql && \
    docker-php-ext-install mysqli && \
    docker-php-ext-install curl && \
    docker-php-ext-install zip && \
    docker-php-ext-install -j$(nproc) intl && \
    docker-php-ext-install mbstring && \
    docker-php-ext-install gettext && \
    docker-php-ext-install calendar && \
    docker-php-ext-install exif && \
	docker-php-ext-install gd && \
	docker-php-ext-configure imap --with-kerberos --with-imap-ssl && \
	docker-php-ext-install imap && \
	docker-php-ext-install soap && \
	docker-php-ext-install bcmath && \
	docker-php-ext-install ldap && \
	docker-php-ext-enable ldap && \
	docker-php-ext-install sodium

# Install Freetype
RUN apt-get install -y --no-install-recommends --fix-missing libfreetype6-dev libjpeg62-turbo-dev libpng-dev && \
    docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd

# Enable Apache modules
RUN a2enmod rewrite headers

# Install MailHog mhsendmail
RUN mkdir -p /root/gocode && \
	export GOPATH=/root/gocode && \
	go get github.com/mailhog/mhsendmail && \
	cp /root/gocode/bin/mhsendmail /usr/local/bin/

# Install Node.js
RUN mkdir -p /etc/apt/keyrings && \
	curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg && \
	echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_20.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list && \
	apt-get update -y --fix-missing && \
	apt-get install -y --no-install-recommends --fix-missing nodejs

# Install Yarn
RUN npm install --global yarn

# Install Bun
RUN curl -fsSL https://bun.sh/install | bash

RUN usermod -u 1000 www-data

# Update & upgrade & autoremove
RUN apt-get update -y --fix-missing && \
	apt-get upgrade -y --fix-missing && \
	apt-get autoremove -y --fix-missing && \
	composer self-update && \
	npm update --global npm yarn

# Cleanup
RUN rm -rf /usr/src/* && \
	rm -rf /var/lib/apt/lists/*