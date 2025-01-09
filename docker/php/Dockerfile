FROM php:8.4.2
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# PECL for code coverage
RUN pecl install pcov && docker-php-ext-enable pcov

# git and zip for composer, intl for php
RUN apt-get update && apt-get install -y git zip libicu-dev && docker-php-ext-install intl && apt-get clean && rm -rf /var/lib/apt/lists/*
