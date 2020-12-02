FROM php:7.4-apache

WORKDIR /var/www/html
# Install dependencies
RUN apt update && apt install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    zlib1g-dev libzip-dev sendmail

# Install extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli exif pcntl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd


# Copy existing application directory contents
COPY . /var/www/html


# Enable Apache mod_rewrite
RUN a2enmod rewrite

RUN pecl install xdebug-2.9.5 && docker-php-ext-enable xdebug
RUN echo 'zend_extension=/usr/local/lib/php/extensions/no-debug-non-zts-20180731/xdebug.so"' >> /usr/local/etc/php/php.ini-production
RUN echo 'xdebug.remote_port=9000' >> /usr/local/etc/php/php.ini-production
RUN echo 'xdebug.remote_enable=1' >> /usr/local/etc/php/php.ini-production
RUN echo 'xdebug.remote_connect_back=1' >> /usr/local/etc/php/php.ini-production
# SEndMail
RUN echo "sendmail_path=/usr/sbin/sendmail -t -i" >> /usr/local/etc/php/conf.d/sendmail.ini
RUN sed -i '/#!\/bin\/sh/aservice sendmail restart' /usr/local/bin/docker-php-entrypoint
RUN sed -i '/#!\/bin\/sh/aecho "$(hostname -i)\t$(hostname) $(hostname).localhost" >> /etc/hosts' /usr/local/bin/docker-php-entrypoint

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*