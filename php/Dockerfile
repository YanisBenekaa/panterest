FROM php:7.4-apache

COPY composer.json composer.lock package.json package-lock.json /var/www/html/panterest/

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf \
&& apt-get update \
&& apt-get install -y \
&& apt-get autoremove -y \
&& docker-php-ext-install mysqli pdo pdo_mysql exif pcntl bcmath \
&& apt-get install curl -y \
&& apt-get install git -y\
&& apt-get install zip -y\
&& apt-get install -y \
		libfreetype6-dev \
		libpng-dev \
		libjpeg-dev \
&& docker-php-ext-configure gd \
		--with-freetype \
		--with-jpeg \
&& docker-php-ext-install -j$(nproc) \
		gd \
&& curl -sL https://deb.nodesource.com/setup_14.x | bash \
&& apt-get install nodejs yarn -y \
&& curl -sS https://get.symfony.com/cli/installer | bash \
&& mv /root/.symfony/bin/symfony /usr/local/bin/symfony

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html/panterest/

RUN symfony composer update

RUN npm i --force

RUN npm run build

USER 1000:1000