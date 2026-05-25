FROM php:8.3-apache

# Instalar extensiones PHP necesarias
RUN apt-get update && apt-get install -y \
    libintl-dev \
    libgd-dev \
    libzip-dev \
    git \
    curl \
    && docker-php-ext-install intl gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Habilitar mod_rewrite para CodeIgniter
RUN a2enmod rewrite

# Configurar Apache para servir desde /public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

# Copiar archivos del proyecto
COPY . .

# Instalar dependencias de Composer
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev --optimize-autoloader

EXPOSE 80
