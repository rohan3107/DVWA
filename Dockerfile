FROM docker.io/library/php:8-apache

# Create a non-root user to run the application
RUN groupadd -g 1000 appuser && \
    useradd -r -u 1000 -g appuser appuser

LABEL org.opencontainers.image.source=https://github.com/digininja/DVWA
LABEL org.opencontainers.image.description="DVWA pre-built image."
LABEL org.opencontainers.image.licenses="gpl-3.0"

WORKDIR /var/www/html

# https://www.php.net/manual/en/image.installation.php
RUN apt-get update \
 && export DEBIAN_FRONTEND=noninteractive \
 && apt-get install -y --no-install-recommends zlib1g-dev libpng-dev libjpeg-dev libfreetype6-dev iputils-ping \
 && apt-get clean -y && rm -rf /var/lib/apt/lists/* \
 && docker-php-ext-configure gd --with-jpeg --with-freetype \
 # Use pdo_sqlite instead of pdo_mysql if you want to use sqlite
 && docker-php-ext-install gd mysqli pdo pdo_mysql

COPY . .
RUN chown -R appuser:appuser /var/www/html

COPY config/config.inc.php.dist config/config.inc.php
RUN chown appuser:appuser config/config.inc.php

# Switch to non-root user
USER appuser

