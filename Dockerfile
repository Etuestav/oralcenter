FROM php:8.3-apache

# Instalar extensiones PHP necesarias
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libgd-dev \
    libzip-dev \
    git \
    curl \
    && docker-php-ext-install intl gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Deshabilitar MPM prefork por defecto y habilitar rewrite
RUN a2dismod mpm_prefork && a2enmod mpm_prefork && a2enmod rewrite

# Configurar Apache para servir desde /public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

# Copiar archivos del proyecto
COPY . .

# Instalar dependencias de Composer
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev --optimize-autoloader

EXPOSE 80
