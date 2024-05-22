FROM php
COPY --from=composer /usr/bin/composer /usr/bin/composer

# PECL for code coverage
RUN pecl install pcov && docker-php-ext-enable pcov

# git and zip for composer
RUN apt-get update && apt-get install -y git zip
