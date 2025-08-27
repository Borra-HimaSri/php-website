FROM php:8.2-apache

# Install PostgreSQL PDO and pgsql extension
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql pgsql

# Optional: install mysqli if you still need it
RUN docker-php-ext-install mysqli

COPY . /var/www/html/

EXPOSE 80
