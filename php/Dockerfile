ARG PHP_VERSION=7.3
FROM php:${PHP_VERSION}-fpm

ARG GRPC="enable"
ARG PROTOBUF="enable"
ARG EXTENSIONS

RUN apt-get update && apt-get install -y -q zlib1g-dev curl git && apt-get clean
# RUN apt-get update && apt-get install -y -q git rake ruby-ronn zlib1g-dev && apt-get clean

RUN if [ "$GRPC" = "enable" ] ; then pecl install grpc ; else echo $GRPC ; fi
RUN if [ "$GRPC" = "enable" ] ; then docker-php-ext-enable grpc ; else echo $GRPC ; fi
RUN if [ "$PROTOBUF" = "enable" ] ; then pecl install protobuf ; fi
RUN if [ "$PROTOBUF" = "enable" ] ; then docker-php-ext-enable protobuf ; else echo ${GRPC} ; fi

RUN if [ "$EXTENSIONS" != "" ] ; then pecl install $EXTENSIONS ; fi
RUN if [ "$EXTENSIONS" != "" ] ; then docker-php-ext-enable $EXTENSIONS ; fi

RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

CMD ["php-fpm"]
