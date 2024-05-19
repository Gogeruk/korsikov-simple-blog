FROM php:8.2-fpm

# General stuff
RUN apt update \
    && apt install -y \
        zlib1g-dev \
        libicu-dev \
        libffi-dev \
        librabbitmq-dev \
        libssh-dev \
        libgmp-dev \
        libssl-dev \
        libzip-dev \
        curl \
        g++ \
        git \
        gnupg \
        wget \
        zip \
        vim \
        nano \
        cmake

# PHP stuff
RUN docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    && docker-php-ext-install opcache \
    && docker-php-ext-install pdo \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install pcntl \
    && docker-php-ext-install sockets \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && pecl install amqp \
    && docker-php-ext-enable amqp \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && docker-php-ext-install intl

# Set the working directory
WORKDIR /var/www/project

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set max execution time
RUN echo "max_execution_time = 300" >> /usr/local/etc/php/conf.d/docker-php-maxexectime.ini

# Set memory limit
RUN echo "memory_limit = 512M" >> /usr/local/etc/php/conf.d/docker-php-memorylimit.ini

# Set upload max filesize
RUN echo "upload_max_filesize = 100M" >> /usr/local/etc/php/conf.d/docker-php-uploadmaxfilesize.ini

# Set post max size
RUN echo "post_max_size = 100M" >> /usr/local/etc/php/conf.d/docker-php-postmaxsize.ini