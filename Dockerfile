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

# Environment variables (can be overridden by docker-compose)
ENV ADMIN_PASSWORD=changeme
ENV TOP_BANNER_TEXT="✨ WELCOME TO THE ULTIMATE 90s YANKEES KARAOKE REQUEST PAGE ✨ REQUEST YOUR SONGS NOW ✨"
ENV BOTTOM_BANNER_TEXT="✨ 90s Yankees Karaoke Vibes ✨ Only Emojis, No Images ✨"

EXPOSE 80
