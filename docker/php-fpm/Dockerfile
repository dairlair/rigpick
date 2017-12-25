FROM php:7.2-fpm

## Install modules
RUN apt-get update
RUN apt-get install -y libpq-dev libzip-dev libpng-dev bzip2 wget git --no-install-recommends
RUN docker-php-ext-install pdo zip pdo_pgsql bcmath gd

# Clear unused files - sources list
RUN rm -rf /var/lib/apt/lists/*

# Install composer
RUN wget http://getcomposer.org/composer.phar && \
    chmod +x composer.phar && \
    mv composer.phar /usr/local/bin/composer

RUN ln -s /usr/local/bin/composer /usr/bin/composers