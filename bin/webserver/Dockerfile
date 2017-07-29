FROM php:7.0-apache

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get update && apt-get install -y g++ \
		libfreetype6-dev libjpeg62-turbo-dev libmcrypt-dev \
		libpng12-dev zlib1g-dev libpng-dev libicu-dev libcurl4-openssl-dev libxml2-dev

RUN docker-php-ext-install mysqli mbstring zip intl mcrypt curl json
RUN docker-php-ext-install iconv xml xmlrpc 

RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

RUN pecl install redis-3.1.0 \
    && pecl install xdebug-2.5.0 \
    && docker-php-ext-enable redis xdebug

# Enable apache modules
RUN a2enmod rewrite headers