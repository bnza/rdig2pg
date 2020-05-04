FROM php:7.4-cli-alpine

ARG GID
ARG UID
ARG APP_ENV

RUN set -xe \
    && apk add --no-cache --update --virtual .phpize-deps $PHPIZE_DEPS \
    && apk add git bash postgresql-dev freetype-dev libjpeg-turbo-dev libpng-dev libzip-dev \
    && docker-php-ext-install pgsql zip pdo_pgsql pdo_mysql gd \
    && pecl install xdebug-2.9.0 \
    && docker-php-ext-enable xdebug \
    && rm -rf /usr/share/php \
    && rm -rf /tmp/* \
    && apk del .phpize-deps

RUN curl -Ss https://getcomposer.org/installer | php -- --install-dir /usr/local/bin --filename=composer \
    && chmod a+x /usr/local/bin/composer && wget https://get.symfony.com/cli/installer -O - | bash \
    && mv ~/.symfony/bin/symfony /usr/local/bin/symfony \
    && adduser --disabled-password --gecos '' -u $UID -g $GID rdig

WORKDIR /opt/project

USER rdig
