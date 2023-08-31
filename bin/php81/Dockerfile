FROM php:8.1.12-apache-buster

# Surpresses debconf complaints of trying to install apt packages interactively
# https://github.com/moby/moby/issues/4032#issuecomment-192327844

ARG DEBIAN_FRONTEND=noninteractive

# Update
RUN apt-get -y update --fix-missing && \
    apt-get -y --no-install-recommends install apt-utils

# Install useful tools and install important libaries
RUN apt-get -y update --fix-missing && \
    apt-get -y --no-install-recommends install --fix-missing nano wget dialog librdkafka-dev libsqlite3-dev libsodium-dev libsqlite3-0 && \
    apt-get -y --no-install-recommends install --fix-missing default-mysql-client zlib1g-dev libzip-dev libpq-dev libicu-dev && \
    apt-get -y --no-install-recommends install --fix-missing apt-utils build-essential git curl libonig-dev && \
    apt-get -y --no-install-recommends install --fix-missing libcurl4 libcurl4-openssl-dev zip golang-go openssl libc-client-dev libkrb5-dev && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install ldap
RUN apt-get -y update --fix-missing && \
	apt-get -y --no-install-recommends install --fix-missing libldap2-dev libnss-ldap libpam-ldap ldap-utils

# Install xdebug
RUN pecl install xdebug-3.2.0 && \
    docker-php-ext-enable xdebug

# Install redis
RUN pecl install redis-5.3.3 && \
    docker-php-ext-enable redis

# Install imagick
RUN apt-get -y update --fix-missing && \
    apt-get -y --no-install-recommends install --fix-missing libmagickwand-dev

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
	docker-php-ext-install sodium

# Install Freetype
RUN apt-get -y update --fix-missing && \
    apt-get -y --no-install-recommends install --fix-missing libfreetype6-dev libjpeg62-turbo-dev libpng-dev && \
    docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd

# Install MailHog
RUN mkdir -p /root/gocode && \
	export GOPATH=/root/gocode && \
	go get github.com/mailhog/mhsendmail && \
	cp /root/gocode/bin/mhsendmail /usr/local/bin/

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_19.x | bash - && \
	apt-get -y --no-install-recommends install nodejs

# Enable apache modules
RUN a2enmod rewrite headers

RUN usermod -u 1000 www-data

# Cleanup
RUN rm -rf /usr/src/* && \
	rm -rf /var/lib/apt/lists/*