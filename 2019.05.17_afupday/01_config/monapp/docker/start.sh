#! /bin/bash

mkdir /var/log/nginx
mkdir /var/log/app

# Do not use this in prod !
chmod a+rw /var/log/app

# Run config cache generation and route generation
php artisan config:cache
php artisan route:cache

# Start supervisord and services
exec /usr/bin/supervisord -n -c /etc/supervisor/supervisord.conf