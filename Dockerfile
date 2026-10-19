# The app has no runtime dependencies, so composer only writes the autoloader.
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json ./
COPY app app
RUN composer dump-autoload --no-dev --classmap-authoritative

# PHP's built-in server: one process, enough for a small demo behind a CDN.
FROM php:8.4-cli-alpine
WORKDIR /app
COPY --from=vendor /app/vendor vendor
COPY app app
COPY database database
COPY public public
# Drops the X-Powered-By header; the image reads extra settings from conf.d.
COPY docker/expose-php.ini /usr/local/etc/php/conf.d/expose-php.ini
# The database lives on a volume mounted here; a new volume takes this owner.
RUN mkdir /data && chown www-data:www-data /data
ENV DB_DSN=sqlite:/data/galaxy.sqlite \
    PHP_CLI_SERVER_WORKERS=4
USER www-data
EXPOSE 8080
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public", "public/index.php"]
