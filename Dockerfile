FROM php:8.2-apache

# Enable mod_rewrite
RUN a2enmod rewrite

# Configure Apache to allow .htaccess overrides (even if not strictly needed here)
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Copy project files
COPY . /var/www/html

# Set working directory
WORKDIR /var/www/html

# Ensure proper permissions for songs.json (will be created by PHP if missing)
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
