FROM php:8.2-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libgmp-dev \
    libmariadb-dev \
    curl \
    gnupg2 \
    lsb-release \
    ca-certificates && \
    # Install Node.js 18.x and npm
    curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs && \
    # Install PHP extensions
    docker-php-ext-install gmp pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app
COPY . /app

# Install PHP dependencies
RUN composer install

# Install Node.js dependencies (npm install)
RUN npm install

EXPOSE 8000
CMD php artisan serve --host=0.0.0.0 --port=8000
