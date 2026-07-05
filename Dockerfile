# Dockerfile - php:8.2-apache with mod_rewrite

FROM php:8.2-apache

# Enable mod_rewrite
RUN a2enmod rewrite

# Configure Apache to allow .htaccess overrides (optional but common)
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html

# Set environment variable for admin password (can be overridden by docker-compose)
ENV ADMIN_PASSWORD=changeme

# Expose port 80 (handled by docker-compose)
EXPOSE 80
