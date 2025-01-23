FROM php:7.3.33-apache

# install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

# install nodejs and npm
RUN apt update && \
    apt install nodejs npm  -y

# install libpng-dev
RUN apt install libpng-dev -y

# install libfreetype6-dev libjpeg62-turbo-dev
RUN apt install libfreetype6-dev libjpeg62-turbo-dev -y

# install cron
RUN apt install vim -y

# install zip unzip
RUN apt install zip unzip

# install zip unzip
RUN apt install cron -y

# libicu untuk ext-intl
RUN apt install libicu-dev -y

# libicu untuk ext-intl
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
&& docker-php-ext-install gd

# install extension
RUN docker-php-ext-install \
    mysqli \ 
    pdo_mysql \
    intl

RUN mkdir /cphalcon
    
RUN git clone https://github.com/phalcon/cphalcon  /cphalcon \
    && cd /cphalcon/build \
    && git checkout origin/3.4.x \
    && ./install

COPY ./docker/vhost/custom.conf /etc/apache2/sites-available/000-default.conf

COPY ./docker/php /usr/local/etc/php/conf.d

EXPOSE 80

COPY ./docker/crontab/crontab /etc/cron.d/cron-kehadiran

RUN chmod 0644  /etc/cron.d/cron-kehadiran

## Apply crond
RUN crontab  /etc/cron.d/cron-kehadiran

RUN touch /var/log/cron.log

WORKDIR /var/www/html

COPY ./ /var/www/html/

RUN apt install git -y

RUN composer install

RUN a2enmod rewrite headers proxy proxy_http

CMD cron && /usr/sbin/apache2ctl -D FOREGROUND
