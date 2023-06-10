# Use a base image with PHP 8.0 and Apache
FROM php:8.1-apache

# Copy application files to the container
COPY ./laravel /var/www/html
RUN chown -R www-data:www-data /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && docker-php-ext-install zip pdo_mysql

# Optimize Composer installation
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ENV COMPOSER_ALLOW_SUPERUSER=1

# Increase Composer timeout
RUN composer config --global process-timeout 2000

# Install Laravel dependencies
WORKDIR /var/www/html
RUN composer install

# Generate Laravel application key
RUN php artisan key:generate

# Generate Laravel table.
COPY startdb.sh /usr/bin/startdb.sh
RUN chmod +x /usr/bin/startdb.sh
ENTRYPOINT ["startdb.sh"]

# Enable Apache modules and set document root
RUN a2enmod rewrite
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Optimize PHP configuration
RUN echo "opcache.enable_cli=1" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini
RUN echo "realpath_cache_size=4096K" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini

# Expose port 8110 for web traffic
EXPOSE 8110

# Start Apache in the foreground when the container starts
CMD ["apache2-foreground"]
