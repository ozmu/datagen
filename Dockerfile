FROM ubuntu:18.04

# Create app dir
RUN mkdir -p /home/app

# Set working directory
WORKDIR /home/app

# Update
RUN apt-get update

# Install Software Properties Common
RUN apt-get install -y software-properties-common

# Add PHP7.3 repo
RUN LC_ALL=C.UTF-8 add-apt-repository -y ppa:ondrej/php

# Update timezone to Istanbul
ENV DEBIAN_FRONTEND=noninteractive
RUN ln -fs /usr/share/zoneinfo/Europe/Istanbul /etc/localtime
RUN apt-get -y install tzdata
RUN dpkg-reconfigure --frontend noninteractive tzdata

# Install packages
RUN apt-get install -y php7.3 \
    php7.3-cli \
    php7.3-common \
    php7.3-xml \
    php7.3-fpm \
    php7.3-mysql \
    php7.3-mbstring \
    zip \
    unzip \
    php7.3-zip \
    git \
    curl \
    apache2 \
    supervisor

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy existing application directory contents
COPY . /home/app

# Add dependencies
RUN composer install

# Build web server (Apache2)
COPY apache2/conf.d/app.conf /etc/apache2/sites-available/
RUN find /etc/apache2/sites-enabled/ -type l -exec rm -if "{}" \;
RUN a2ensite app.conf

# Laravel
# RUN php artisan key:generate
RUN php artisan config:cache

# Install NodeJS 10
RUN curl -sL https://deb.nodesource.com/setup_10.x | bash -
RUN apt install -y nodejs

# Install NPM dependencies and compile
RUN npm install
RUN npm run prod

# Supervisor copy configuration and run
COPY ./supervisor/queue.conf /etc/supervisor/conf.d/
RUN service supervisor start
RUN supervisorctl start queue:*

# Copy existing application directory permissions
RUN chown -R www-data: /home/app

# Change current user to www
USER www-data

# Expose port 80 and start nginx server
EXPOSE 80
CMD ["apachectl", "-D", "FOREGROUND"]