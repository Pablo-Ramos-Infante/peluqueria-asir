FROM php:8.2-apache

# Instalar extensiones necesarias
RUN docker-php-ext-install pdo pdo_mysql

# Copiar el proyecto al contenedor
COPY . /var/www/html/

# Establecer la carpeta pública como raíz del servidor
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Actualizar configuración de Apache
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Habilitar mod_rewrite
RUN a2enmod rewrite

EXPOSE 80

