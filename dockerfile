FROM php:8.1-apache

# PHP 확장 설치
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd mysqli \
    && docker-php-ext-enable mysqli

# Apache 모듈 활성화
RUN a2enmod rewrite

# 소스 복사
COPY . /var/www/html

# 포트 노출
EXPOSE 80
