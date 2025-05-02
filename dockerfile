FROM php:8.1-apache
# 필요한 PHP 확장 설치
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# 필요한 경우 mod_rewrite 같은 apache 모듈도 활성화
RUN a2enmod rewrite

COPY . /var/www/html
EXPOSE 80