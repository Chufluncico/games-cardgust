FROM dunglas/frankenphp:latest

WORKDIR /app

# Instalar extensiones necesarias
RUN install-php-extensions \
    pdo_mysql \
    mbstring \
    bcmath \
    gd \
    zip

# Copiar proyecto
COPY . .

# Instalar composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instalar dependencias Laravel
RUN composer install --no-dev --optimize-autoloader

# Permisos
RUN chmod -R 775 storage bootstrap/cache

# Configuración Caddy (FrankenPHP)
RUN printf ":80 {\n    root * /app/public\n    php_server\n}\n" > /etc/frankenphp/Caddyfile