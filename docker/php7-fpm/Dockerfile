ARG PHP_TAG_VERSION=${PHP_TAG_VERSION}
FROM yiisoftware/yii2-php:${PHP_TAG_VERSION:-7.2-fpm-18.12.0}

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libpq-dev \
        libzip-dev \
        libmemcached-dev \
        memcached \
        libcurl4-gnutls-dev \
        libxml2-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
    && pecl install \
        memcached \
        xdebug \
    && docker-php-ext-enable \
        memcached \
        opcache

COPY ./config/php-fpm.d/ /usr/local/etc/php-fpm.d/
COPY entrypoint.sh /entrypoint.sh
COPY ./config/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

ADD https://raw.githubusercontent.com/vishnubob/wait-for-it/master/wait-for-it.sh /bin/wait-for-it

RUN chmod a+x /bin/wait-for-it \
    && chmod a+x /entrypoint.sh \
    && usermod --non-unique --uid 1000 www-data \
    && groupmod --non-unique --gid 1000 www-data
WORKDIR /app
ENTRYPOINT [ "/entrypoint.sh" ]
CMD [ "php-fpm" ]
