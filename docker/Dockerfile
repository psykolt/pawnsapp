FROM php:8.4-fpm

ARG user
ARG uid

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions \
    zip
RUN apt update && apt install -y \
    git curl libpng-dev libonig-dev libxml2-dev\
    curl libbz2-dev libmcrypt-dev zip unzip \
    nginx openssh-server vim \
    libcurl4-openssl-dev pkg-config libssl-dev \
    && mkdir -p /var/run/sshd
RUN apt clean && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd
RUN docker-php-ext-enable sodium

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

COPY --chown=www-data:www-data . /var/www

WORKDIR /var/www

COPY docker/nginx/default.conf /etc/nginx/sites-enabled/default
COPY --chmod=755 docker/entrypoint.sh /etc/entrypoint.sh
COPY docker/php/php.ini /usr/local/etc/php/conf.d/php.ini

RUN chmod +x /etc/entrypoint.sh

ENTRYPOINT ["/etc/entrypoint.sh"]
