FROM php:8.3-apache



# Install system dependencies
RUN apt-get update \
    && apt-get install -y \
        libicu-dev \
        libpq-dev \
        libzip-dev \
        libpng-dev \
        unzip \
        jq \
        git \
        cron \
        nano \
        supervisor

# Install PHP extensions
RUN docker-php-ext-install \
    intl \
    pdo \
    pdo_pgsql \
    gd \
    zip \
    pdo \
    pdo_mysql

# Install APCU
RUN pecl install apcu
RUN docker-php-ext-enable apcu

# Install redis
RUN pecl install redis
RUN docker-php-ext-enable redis

# Install symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# Install Composer CLI
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# You can add your own aliases here
RUN echo 'alias console="php /var/www/html/bin/console"' >> ~/.bashrc
RUN echo 'alias phpstan="/var/www/html/vendor/phpstan/phpstan/phpstan"' >> ~/.bashrc
RUN echo 'alias dsu="/var/www/html/bin/console d:s:u --force --complete"' >> ~/.bashrc

RUN echo "umask 0000" >> /root/.bashrc

WORKDIR /var/www
ENV COMPOSER_ALLOW_SUPERUSER=1
COPY ./playground/app/.env /var/www/.env
RUN composer create-project apie/apie-project-starter html -s dev
COPY ./playground/edit-composer.json tmp.json
RUN jq -s 'add' tmp.json ./html/composer.json > tmp-merged.json
RUN jq '.autoload["psr-4"] += {"App\\Apie\\Maker\\": "generated/"}' tmp-merged.json > /var/www/html/composer.json
WORKDIR /var/www/html
RUN mkdir generated
RUN chmod 777 /var/www/html/generated
COPY ./playground/app/apie.yaml /var/www/html/config/packages/apie.yaml
COPY ./playground/app/messenger.yaml /var/www/html/config/packages/messenger.yaml
COPY ./playground/sqlite.db /var/www/html/var/db.sqlite
RUN chmod 666 /var/www/html/config/packages/apie.yaml /var/www/html/var/db.sqlite
RUN chmod 777 /var/www/html/var
RUN chown www-data:www-data /var/www/html/config/packages/apie.yaml /var/www/html/var /var/www/html/var/db.sqlite
RUN rm -rf vendor
RUN composer config --no-plugins allow-plugins.apie/apie-common-plugin true
RUN mkdir /packages
COPY ../../packages /packages
RUN composer require 'apie/cms-layout-ionic:*' 'apie/cms-layout-ugly:*' 'apie/ai-instructor:*' 'symfony/messenger:7.*' 'symfony/redis-messenger:7.*' --no-install
RUN composer update

# Setup crontab
COPY ./playground/.docker/crontab /var/www/html/.docker/crontab
RUN crontab /var/www/html/.docker/crontab

# Start Supervisor, which will run cron -f by default.
CMD ["/usr/bin/supervisord"]