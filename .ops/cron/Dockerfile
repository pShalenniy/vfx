ARG PHP_IMAGE
FROM ${PHP_IMAGE}:latest
LABEL company="amgrade" type="laravel" project="vfx" image="cron"
USER root
WORKDIR /cron
COPY ".ops/cron/tasks.conf" "./tasks"
RUN /usr/bin/crontab -u www-data /cron/tasks
