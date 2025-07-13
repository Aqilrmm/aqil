FROM php:8.2-apache

# Install ekstensi PHP yang dibutuhkan (intl, mysqli, pdo, dom, dll)
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libxml2-dev \
    && docker-php-ext-install intl mysqli pdo pdo_mysql dom \
    && docker-php-ext-enable intl pdo_mysql dom

# Aktifkan mod_rewrite Apache
RUN a2enmod rewrite

# Copy file proyek CodeIgniter ke dalam container
COPY . /var/www/html

# Copy konfigurasi virtual host Apache
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Set permission folder agar bisa diakses Apache
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Set direktori kerja ke folder public CodeIgniter 4
WORKDIR /var/www/html/public
