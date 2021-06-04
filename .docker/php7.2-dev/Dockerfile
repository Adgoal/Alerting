FROM php:7.2-cli

RUN apt-get update && apt-get install -y git unzip

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_MEMORY_LIMIT -1

RUN mkdir /.composer_cache
ENV COMPOSER_CACHE_DIR /.composer_cache

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=1.10.16

RUN composer -vvv global require hirak/prestissimo

# php extensions

# -------------------- Installing PHP Extension: redis --------------------
RUN set -eux \
    # Installation: Generic
    # Type:         PECL extension
    # Default:      Pecl commandtests/unit/TestCase.php
    && pecl install redis \
    && docker-php-ext-enable redis \
    && true

# -------------------- Installing PHP Extension: xdebug --------------------
RUN set -eux \
    # Installation: Generic
    # Type:         PECL extension
    # Default:      Pecl command
    && pecl install xdebug-2.9.6 \
    && docker-php-ext-enable xdebug \
    && true
