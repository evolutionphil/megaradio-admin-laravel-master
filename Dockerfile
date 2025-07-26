FROM php:8.1-cli

RUN apt update && apt install -y \
    git \
    curl \
    zip \
    unzip \
    pkg-config \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libmagickwand-dev \
    libssl-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN curl -sL https://deb.nodesource.com/setup_18.x | bash
RUN apt-get install nodejs
RUN node -v

WORKDIR /app
COPY . /app

RUN export COMPOSER_ALLOW_SUPERUSER=1

RUN composer update

EXPOSE 8000

CMD php artisan serve --host 0.0.0.0
