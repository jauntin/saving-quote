FROM ghcr.io/jauntin/php-fpm:2.0.3
RUN apk add libzip-dev
RUN docker-php-ext-install zip
ARG ENV=production
WORKDIR /app

COPY composer.* .
RUN if [ "$ENV" = "production" ] ; then \
    composer install --no-dev --optimize-autoloader --no-scripts; \
    else \
    apk add diff-pdf; \
    docker-php-ext-enable pcov; \
    composer install --optimize-autoloader --no-scripts; \
    fi; \
    rm -rf ~/.composer
COPY --chown=www-data:www-data . /app
RUN if [ "$ENV" = "production" ] ; then \
    composer dump-autoload --no-scripts --no-dev --optimize; \
    else \
    composer dump-autoload --no-scripts --optimize; \
    fi; \
    printf '[PHP]\npost_max_size = 32M\nupload_max_filesize = 32M\n' > /usr/local/etc/php/conf.d/customizations.ini && \
    rm -rf /var/www/html && \
    ln -s /app/public /var/www/html
