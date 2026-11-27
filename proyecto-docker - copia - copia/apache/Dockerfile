FROM php:8.3-apache

# Instalar extensión PDO para MySQL y limpiar caché de apt
RUN docker-php-ext-install pdo_mysql \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

# Usar configuración recomendada de PHP en producción (errores a log, no en pantalla)
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Copiar el código de la aplicación al directorio público de Apache
COPY app/index.php /var/www/html/
COPY app/config/ /var/www/html/config/
COPY app/includes/ /var/www/html/includes/
COPY app/css/ /var/www/html/css/

# Ajustar propietario y permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80