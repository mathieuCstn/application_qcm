FROM php:8.3-cli

# Installer les dépendances système
RUN apt-get update \
&& apt-get install -y \
    git \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    libonig-dev \
    unzip \
    wget \
    zip \
    curl \
    postgresql-client \
&& apt-get clean \
&& rm -rf /var/lib/apt/lists/*


# PHP Extensions
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions \
    bcmath \
    gd \
    intl \
    mbstring \
    pdo_pgsql \
    zip \
    opcache

# Télécharger et installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Télécharger et installer Symfony CLI
RUN wget https://get.symfony.com/cli/installer -O - | bash \
&& mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Indiquer que le port 8000 est exposé
EXPOSE 8000

# Définir le répertoire de travail
WORKDIR /app