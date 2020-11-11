FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
	vim \
  libzip-dev \
  libpng-dev \
  zip \
  libfreetype6-dev \
  libjpeg62-turbo-dev \
  libpng-dev \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install mysqli pdo_mysql zip bcmath -j$(nproc) gd

# RUN adduser -D dockuser
# USER dockuser
# RUN docker-php-ext-install mbstring

# RUN docker-php-ext-install zip

RUN docker-php-ext-install gd

EXPOSE 9001