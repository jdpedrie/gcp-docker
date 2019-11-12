ARG PHP_VERSION=7.3
FROM php:${PHP_VERSION}-fpm

ARG GRPC="enable"
ARG PROTOBUF="enable"
ARG OLD_PHP="false"
ARG EXTENSIONS

RUN echo ${PHP_VERSION}

# install from backports for 5.4.
RUN if [ `echo ${PHP_VERSION} | grep -c "5.4" ` -eq 1 ] ; then OLD_PHP="true"; sed -i '/jessie-updates/d' /etc/apt/sources.list ; fi
RUN apt-get update && apt-get install -y -q zlib1g-dev curl git && apt-get clean

RUN if [ "$GRPC" = "enable" ] ; then pecl install grpc ; else echo $GRPC ; fi
RUN if [ "$GRPC" = "enable" ] ; then docker-php-ext-enable grpc ; else echo $GRPC ; fi
RUN if [ "$PROTOBUF" = "enable" ] ; then pecl install protobuf ; fi
RUN if [ "$PROTOBUF" = "enable" ] ; then docker-php-ext-enable protobuf ; else echo ${GRPC} ; fi

RUN if [ "$EXTENSIONS" != "" ] ; then pecl install $EXTENSIONS ; fi
RUN if [ "$EXTENSIONS" != "" ] ; then docker-php-ext-enable $EXTENSIONS ; fi

RUN docker-php-ext-install bcmath

# skip xdebug on 5.4.
# RUN if [ `echo ${PHP_VERSION} | grep -c "5.4" ` -eq 0 ] ; then pecl install xdebug; fi
# RUN if [ `echo ${PHP_VERSION} | grep -c "5.4" ` -eq 0 ] ; then docker-php-ext-enable xdebug; fi

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

CMD ["php-fpm"]
