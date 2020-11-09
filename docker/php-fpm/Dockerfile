FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
	vim \
  && docker-php-ext-install mysqli pdo_mysql
# RUN adduser -D dockuser
# USER dockuser

EXPOSE 9000