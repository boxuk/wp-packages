ARG WORDPRESS_VERSION
FROM wordpress:$WORDPRESS_VERSION

COPY ./wp-config.php /var/www/html/wp-config.php
COPY ./db.php /var/www/html/wp-content/db.php
COPY ./mu-plugins /var/www/html/wp-content/mu-plugins
COPY ./themes /var/www/html/wp-content/themes
