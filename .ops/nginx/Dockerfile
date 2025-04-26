ARG PHP_IMAGE
FROM ${PHP_IMAGE}:latest AS php

FROM nginx:1.23-alpine
LABEL company="amgrade" type="laravel" project="vfx" image="nginx"
WORKDIR /app
COPY --from=php /app/public /app/public
