ARG PHP_VERSION=7.3
FROM php:${PHP_VERSION}-fpm

ARG GRPC="enable"
ARG PROTOBUF="enable"

RUN apt-get update && apt-get install -y -q zlib1g-dev && apt-get clean
# RUN apt-get update && apt-get install -y -q git rake ruby-ronn zlib1g-dev && apt-get clean

RUN if [ "$GRPC" = "enable" ] ; then pecl install grpc ; else echo $GRPC ; fi
RUN if [ "$GRPC" = "enable" ] ; then docker-php-ext-enable grpc ; else echo $GRPC ; fi
RUN if [ "$PROTOBUF" = "enable" ] ; then pecl install protobuf ; fi
RUN if [ "$PROTOBUF" = "enable" ] ; then docker-php-ext-enable protobuf ; else echo ${GRPC} ; fi

# Install Xdebug
# RUN curl -fsSL 'https://xdebug.org/files/xdebug-2.7.2.tgz' -o xdebug.tar.gz \
#     && mkdir -p xdebug \
#     && tar -xf xdebug.tar.gz -C xdebug --strip-components=1 \
#     && rm xdebug.tar.gz \
#     && ( \
#     cd xdebug \
#     && phpize \
#     && ./configure --enable-xdebug \
#     && make -j$(nproc) \
#     && make install \
#     ) \
#     && rm -r xdebug

RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

CMD ["php-fpm"]
