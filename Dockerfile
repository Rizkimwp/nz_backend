FROM php:8.0-apache

# Install ekstensi PHP
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Aktifkan mod_rewrite Apache
RUN a2enmod rewrite

# Salin konfigurasi custom Apache (jika ada)
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Salin semua project ke dalam container
COPY . /var/www/html/

# Ubah permission (opsional)
RUN chown -R www-data:www-data /var/www/html
