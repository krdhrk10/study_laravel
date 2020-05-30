FROM php:latest

ENV DEBIAN_FRONTEND noninteractive
RUN apt-get update -y && apt-get install -y unzip
RUN docker-php-ext-install pdo_mysql

RUN mkdir /var/php -p
WORKDIR /var/php

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
	php -r "if (hash_file('sha384', 'composer-setup.php') === 'e0012edf3e80b6978849f5eff0d4b4e4c79ff1609dd1e613307e16318854d24ae64f26d17af3ef0bf7cfb710ca74755a') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
	php composer-setup.php --filename=composer && \
    mv composer /usr/local/bin/composer && \
	php -r "unlink('composer-setup.php');"

ENV PATH $PATH:/root/.composer/vendor/bin

RUN composer global require "laravel/installer=~1.1"
