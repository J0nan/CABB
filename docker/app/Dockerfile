FROM php:7.4-fpm-alpine3.15

WORKDIR /var/www/html

RUN docker-php-ext-install pdo pdo_mysql bcmath

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY start-install /usr/local/bin/start-install
RUN chmod +x /usr/local/bin/start-install

ENTRYPOINT ["start-install"]