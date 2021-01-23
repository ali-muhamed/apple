#!/bin/bash
set -e
echo "---------------> Wait datebase"
wait-for-it -t 0 mysql:3306
echo "---------------> Wait memcache"
wait-for-it -t 0 memcache:11211

if [ ! -z "$PHP_INI_CUSTOM_CONFIG" ]; then
    echo "---------------> set custom config php.ini"
    printf $PHP_INI_CUSTOM_CONFIG'\n' > /usr/local/etc/php/php.ini
fi

if [ "$PHP_ENABLE_XDEBUG" = 1 ]; then
    if [ ! -z "$PHP_XDEBUG_CONFIG" ]; then
        echo "---------------> set config xdebug.ini"
        printf $PHP_XDEBUG_CONFIG'\n' >> /usr/local/etc/php/conf.d/xdebug.ini
    fi
    
    touch /var/log/xdebug.log
    echo "---------------> start xdebug"
    docker-php-ext-enable xdebug
    chown -R www-data:www-data /var/log/
fi

if [ ! -d "vendor" ]; then
    composer install
    chown -R www-data:www-data ./vendor/
    
    if [ ! -d "vendor/bower" ]; then
        ln -s ../vendor/bower-asset vendor/bower
    fi
    
    php yii migrate/up --interactive=0
    
    if [ "YII_ENV_DEV" = 0 ]; then
        php init --env=Production --overwrite=No
    else
        php init --env=Development --overwrite=No
    fi
fi

exec "$@"
