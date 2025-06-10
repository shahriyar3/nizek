FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    supervisor

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip opcache

RUN pecl install redis && docker-php-ext-enable redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN useradd -G www-data,root -u 1000 -d /home/dev dev
RUN mkdir -p /home/dev/.composer && \
    chown -R dev:dev /home/dev

WORKDIR /app

COPY . /app

COPY --chown=dev:dev . /app

RUN mkdir -p /var/log/supervisor && \
    chown -R root:root /var/log/supervisor

COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY supervisor/queue-workers.conf /etc/supervisor/conf.d/queue-workers.conf

COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini

CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
