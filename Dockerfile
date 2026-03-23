FROM php:8.2-apache

# Instalar extensiones necesarias
RUN docker-php-ext-install pdo pdo_mysql

# Copiar el proyecto al contenedor
COPY . /var/www/html/

# Establecer la carpeta pública como raíz del servidor
WORKDIR /var/www/html/public

# Habilitar mod_rewrite (opcional)
RUN a2enmod rewrite

EXPOSE 80
