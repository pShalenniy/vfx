FROM php:8.1-fpm-alpine AS php
LABEL company="amgrade" type="laravel" project="vfx" image="php"

RUN --mount=id=cache-apk,type=cache,target=/var/cache/apk \
    set -ex; \
    apk update \
    && apk add --no-cache \
      $PHPIZE_DEPS \
      curl \
      git \
      libpng-dev \
      libxml2-dev \
      libzip-dev \
      mysql-client \
      unzip \
      zip \
      zlib-dev \
    && pecl install redis \
    && docker-php-ext-install -j "$(nproc)" bcmath gd pcntl pdo_mysql zip > /dev/null \
    && docker-php-ext-enable redis \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && apk del $PHPIZE_DEPS

WORKDIR /app
RUN chown -R www-data:www-data /app

ARG COMPOSER_ARGS
ENV APP_DEBUG=false
ENV COMPOSER_ARGS ${COMPOSER_ARGS}

COPY --chown=www-data:www-data "composer.*" ./
USER www-data
RUN --mount=id=cache-composer,type=cache,target=/var/www/.composer \
    composer install --no-autoloader --no-progress --no-scripts --no-dev
COPY --chown=www-data:www-data ./ ./
RUN --mount=id=cache-composer,type=cache,target=/var/www/.composer \
    composer install -o $COMPOSER_ARGS \
    && php artisan ziggy:generate ./resources/assets/common/js/generated/routes.js --url=https://vfx.amgrade.dev \
    && php artisan js-translations:extract -D ./resources/assets/common/js/generated/translations.js


FROM node:18-alpine AS node
WORKDIR /app
COPY ["package.json", "yarn.lock", "./"]
RUN mkdir -p /cache/.yarn && yarn config set cache-folder /cache/.yarn
RUN --mount=id=cache-yarn,type=cache,target=/cache/.yarn yarn install
COPY --from=php /app ./
RUN --mount=id=cache-webpack,type=cache,target=/app/node_modules/.cache yarn run prod


FROM php

COPY --from=node --chown=www-data:www-data /app/public/css ./public/css
COPY --from=node --chown=www-data:www-data /app/public/js ./public/js
COPY --from=node --chown=www-data:www-data /app/public/images ./public/images
COPY --from=node --chown=www-data:www-data /app/public/fonts ./public/fonts
COPY --from=node --chown=www-data:www-data /app/public/mix-manifest.json ./public/mix-manifest.json

COPY --chown=www-data:www-data .ops/start.sh ./
RUN chmod +x /app/start.sh \
    && php artisan storage:link \
    && rm -rf \
      .git \
      .ops \
      resources/assets \
      .babelrc \
      .gitignore \
      .gitlab-ci.yml \
      webpack.config.js \
      yarn.lock \
      pages \
      scraping

USER www-data

ARG COMMIT_SHORT_SHA
ENV COMMIT_SHORT_SHA ${CI_COMMIT_SHORT_SHA}

ENTRYPOINT ["/app/start.sh"]
CMD php-fpm
