FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
	vim \
  libzip-dev \
  libpng-dev \
  zip \
  && docker-php-ext-install mysqli pdo_mysql zip

# RUN adduser -D dockuser
# USER dockuser
# RUN docker-php-ext-install mbstring

# RUN docker-php-ext-install zip

RUN docker-php-ext-install gd

EXPOSE 9000