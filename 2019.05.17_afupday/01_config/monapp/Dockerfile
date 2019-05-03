FROM composer:1 as buildphp

COPY composer.lock composer.json /app/

RUN composer install --ignore-platform-reqs \
    --no-ansi \
    --no-autoloader \
    --no-dev \
    --no-interaction \
    --no-scripts

WORKDIR /app
COPY . /app
RUN composer dump-autoload --optimize --classmap-authoritative

# Build final image
FROM php:7-fpm-alpine

ENV php_conf /usr/local/etc/php-fpm.conf
ENV fpm_conf /usr/local/etc/php-fpm.d/www.conf
ENV php_vars /usr/local/etc/php/conf.d/docker-vars.ini

RUN apk --no-cache add nginx supervisor icu-dev postgresql-dev \
    && docker-php-ext-install intl pdo pdo_mysql pdo_pgsql

RUN rm -Rf /etc/nginx/nginx.conf
RUN echo "cgi.fix_pathinfo=0" > ${php_vars} &&\
    echo "upload_max_filesize = 100M"  >> ${php_vars} &&\
    echo "post_max_size = 100M"  >> ${php_vars} &&\
    echo "variables_order = \"EGPCS\""  >> ${php_vars} && \
    echo "memory_limit = 256M"  >> ${php_vars} && \
    sed -i \
        -e "s/;catch_workers_output\s*=\s*yes/catch_workers_output = yes/g" \
        -e "s/pm.max_children = 5/pm.max_children = 2000/g" \
        -e "s/pm.start_servers = 2/pm.start_servers = 10/g" \
        -e "s/pm.min_spare_servers = 1/pm.min_spare_servers = 10/g" \
        -e "s/pm.max_spare_servers = 3/pm.max_spare_servers = 150/g" \
        -e "s/;pm.max_requests = 500/pm.max_requests = 500/g" \
        -e "s/;listen.mode = 0660/listen.mode = 0666/g" \
        -e "s/listen = 127.0.0.1:9000/listen = \/var\/run\/php-fpm.sock/g" \
        -e "s/^;clear_env = no$/clear_env = no/" \
${fpm_conf}

COPY docker/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS="0" \
    PHP_OPCACHE_MAX_ACCELERATED_FILES="10000" \
    PHP_OPCACHE_MEMORY_CONSUMPTION="192" \
    PHP_OPCACHE_MAX_WASTED_PERCENTAGE="10"

# nginx site conf
RUN rm -Rf /etc/nginx/sites-enabled/* && \
    rm -Rf /etc/nginx/sites-available/* && \
    mkdir -p /etc/nginx/sites-available/ && \
    mkdir -p /etc/nginx/sites-enabled/ && \
    mkdir -p /etc/nginx/ssl/ && \
    mkdir -p /run/nginx/ && \
    rm -Rf /var/www/* && \
    mkdir /var/www/html/

ADD docker/supervisord.conf /etc/supervisor/supervisord.conf
ADD docker/nginx.conf /etc/nginx/nginx.conf
ADD docker/nginx-site.conf /etc/nginx/sites-available/default.conf
RUN ln -s /etc/nginx/sites-available/default.conf /etc/nginx/sites-enabled/default.conf

ADD docker/start.sh /start.sh
RUN chmod 755 /start.sh

WORKDIR /app
COPY --from=buildphp /app .

RUN chown -R www-data:www-data /app/storage
EXPOSE 80
CMD ["/bin/sh", "/start.sh"]