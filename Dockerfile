# Usamos una imagen oficial de PHP con Apache integrado
FROM php:8.2-apache

# Instalamos la extensión mysqli que requiere tu proyecto para hablar con la base de datos
RUN docker-php-ext-install mysqli

# Habilitamos el módulo de reescritura de Apache por si acaso usas rutas amigables (.htaccess)
RUN a2enmod rewrite

# Exponemos el puerto 80 interno del contenedor
EXPOSE 80