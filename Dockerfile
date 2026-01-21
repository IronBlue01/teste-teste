FROM php:8.2-fpm

# Instalar dependências
RUN apt-get update && apt-get install -y \
    cron \
    supervisor \
    unzip \
    git \
    curl \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    libcurl4-openssl-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring exif pcntl bcmath

# Install PHP extensions
RUN apt-get update && apt-get install -y \
    libpq-dev && \
    docker-php-ext-install  pdo_pgsql


# Instale dependências do sistema
RUN apt-get update && apt-get install -y \
    libicu-dev \
    && docker-php-ext-install intl

# Instalar Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Criar diretório
WORKDIR /var/www

# Copiar app
COPY . .

# Instalar dependências Laravel
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Permissões
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Copiar cron
COPY crontab /etc/cron.d/laravel-cron
RUN chmod 0644 /etc/cron.d/laravel-cron && crontab /etc/cron.d/laravel-cron

# Copiar config do supervisor
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 9000

CMD ["/usr/bin/supervisord"]
