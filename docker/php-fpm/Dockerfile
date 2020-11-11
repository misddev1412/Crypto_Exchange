FROM php:7.4-fpm

# RUN apt-get update && apt-get install -y \
# 	vim \
#   libzip-dev \
#   libpng-dev \
#   zip \
#   libfreetype6-dev \
#   libjpeg62-turbo-dev \
#   libpng-dev \
#   libmcrypt-dev \
#   libicu-dev \
#   libxml2-dev \
#   # && docker-php-ext-install -j$(nproc) iconv \
#   && docker-php-ext-install mysqli pdo_mysql zip bcmath 
# RUN docker-php-ext-configure gd \
#     --with-gd \
#     --with-webp-dir \
#     --with-jpeg-dir \
#     --with-png-dir \
#     --with-zlib-dir \
#     --with-xpm-dir \
#     --with-freetype-dir

# # RUN adduser -D dockuser
# # USER dockuser
# # RUN docker-php-ext-install mbstring

# # RUN docker-php-ext-install zip

# RUN docker-php-ext-install gd

# define timezone
RUN echo "Asia/Ho_Chi_Minh" > /etc/timezone
RUN dpkg-reconfigure -f noninteractive tzdata
RUN /bin/echo -e "LANG=\"en_US.UTF-8\"" > /etc/default/local

# install dependencies
RUN apt-get update
RUN apt-get install -y --no-install-recommends \
    build-essential \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libpng-dev \
    libwebp-dev \
    curl \
    libcurl4 \
    libcurl4-openssl-dev \
    zlib1g-dev \
    libicu-dev \
    libmemcached-dev \
    memcached \
    default-mysql-client \
    libmagickwand-dev \
    unzip \
    libzip-dev \
    zip \
    nano;

# memcached
RUN pecl install memcached-3.1.5
RUN docker-php-ext-enable memcached

# mcrypt
RUN pecl install mcrypt-1.0.3
RUN docker-php-ext-enable mcrypt

# configure, install and enable all php packages
RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg --with-webp 
RUN docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd
RUN docker-php-ext-configure mysqli --with-mysqli=mysqlnd
RUN docker-php-ext-configure intl
RUN docker-php-ext-configure zip

RUN docker-php-ext-install -j$(nproc) opcache
RUN docker-php-ext-install -j$(nproc) pdo_mysql
RUN docker-php-ext-install -j$(nproc) mysqli
RUN docker-php-ext-install -j$(nproc) pdo
RUN docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-install -j$(nproc) intl
RUN docker-php-ext-install -j$(nproc) zip
RUN docker-php-ext-install -j$(nproc) bcmath


# install imagick
RUN pecl install imagick-3.4.4
RUN docker-php-ext-enable imagick

# install composer
RUN cd /tmp \
    && curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

# clean image
RUN apt-get clean

EXPOSE 9001