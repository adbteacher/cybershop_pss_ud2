FROM php:8.1-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends libcurl4-openssl-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install mysqli curl

RUN a2enmod rewrite

COPY apache-config.conf /etc/apache2/conf-enabled/vuln-lab.conf

RUN mkdir -p /var/www/html/uploads /var/www/html/invoices /var/lab_flags \
    && chown -R www-data:www-data /var/www/html /var/lab_flags
