#!/usr/bin/env bash

# Make sure all the migrations are ran before starting the container
cd /var/www && ./artisan migrate

service nginx start
php-fpm
