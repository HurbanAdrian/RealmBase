# Použijeme oficiálny PHP image s Apache
FROM php:8.2-apache

# Povolenie PDO a MySQL rozšírení pre PHP
RUN docker-php-ext-install pdo pdo_mysql
# nastavenie, aby Apache rešpektoval .htaccess
RUN sed -i 's|AllowOverride None|AllowOverride All|g' /etc/apache2/apache2.conf

# Nastavenie pracovného adresára
WORKDIR /var/www/html

# Skopíruj projektové súbory do kontajnera
COPY ./public /var/www/html
COPY ./app /var/www/html/app
COPY ./config /var/www/html/config
COPY ./database /var/www/html/database
COPY . /var/www/html

# Povolenie mod_rewrite (potrebné pre .htaccess)
RUN a2enmod rewrite

# Nastavenie práv
RUN chown -R www-data:www-data /var/www/html