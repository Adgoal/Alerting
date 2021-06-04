FROM php:7.2-cli

RUN apt-get update && apt-get install -y git unzip

RUN set -eux \
    # Installation: Generic
    # Type:         Built-in extension
    && pecl install ast \
    && docker-php-ext-enable ast \
    && true

# -------------------- Installing PHP Extension: redis --------------------
RUN set -eux \
    # Installation: Generic
    # Type:         PECL extension
    # Default:      Pecl command
    && pecl install redis \
    && docker-php-ext-enable redis \
    && true

# -------------------- Installing PHP Extension: intl --------------------
RUN set -eux \
    # Installation: Generic
    # Type:         Built-in extension
    && apt install -y libicu-dev \
    && docker-php-ext-install -j$(getconf _NPROCESSORS_ONLN) intl \
    && true

COPY php.ini /usr/local/etc/php/conf.d/default-php.ini

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_MEMORY_LIMIT -1

RUN mkdir /.composer_cache
ENV COMPOSER_CACHE_DIR /.composer_cache

RUN mkdir /packages
COPY . /packages/Alerting
WORKDIR /packages/Alerting

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer -vvv install
