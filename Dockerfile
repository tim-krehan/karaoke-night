FROM php:8.2-apache

RUN a2enmod rewrite
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

WORKDIR /var/www/html
COPY . /var/www/html

ENV ADMIN_PASSWORD=changeme
ENV TOP_BANNER_TEXT="✨ WELCOME TO THE ULTIMATE 90s YANKEES KARAOKE REQUEST PAGE ✨"
ENV BOTTOM_BANNER_TEXT="✨ 90s Yankees Karaoke Vibes ✨ Only Emojis, No Images ✨"

EXPOSE 80
