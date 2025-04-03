FROM php:8.2-apache

# Apacheの設定
RUN a2enmod rewrite

# PHPの設定
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini" \
    && echo "display_errors = On" >> "$PHP_INI_DIR/php.ini" \
    && echo "error_reporting = E_ALL" >> "$PHP_INI_DIR/php.ini"

# 作業ディレクトリの設定
WORKDIR /var/www/html

# Apache設定ファイルのコピー
COPY 000-default.conf /etc/apache2/sites-available/

# アプリケーションファイルのコピー
COPY . /var/www/html/

# パーミッションの設定
RUN chown -R www-data:www-data /var/www/html 