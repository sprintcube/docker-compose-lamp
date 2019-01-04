FROM php:7.2-apache

RUN apt-get -y update --fix-missing

# Install useful tools

# Install important libraries

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install xdebug
RUN pecl install xdebug-2.6.0
RUN docker-php-ext-enable xdebug

# Install redis
RUN pecl install redis-4.0.1
RUN docker-php-ext-enable redis

# Other PHP7 Extensions

RUN docker-php-ext-install pdo_mysql 
RUN docker-php-ext-install mysqli

RUN docker-php-ext-install tokenizer
RUN docker-php-ext-install json


# Enable apache modules
RUN a2enmod rewrite headers
