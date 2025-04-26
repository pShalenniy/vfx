#!/bin/sh

set -e

role=${CONTAINER_ROLE:-app}

if [[ "$role" = 'app' ]]; then
    php artisan cache:clear
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache

    exec php-fpm
elif [[ "$role" = 'queue' ]]; then
    exec php artisan queue:work --tries=1
elif [[ "$role" = 'queue-elasticsearch' ]]; then
    exec php artisan queue:work --queue=elasticsearch --tries=1
elif [[ "$role" = 'queue-email' ]]; then
    exec php artisan queue:work --queue=email --tries=1
elif [[ "$role" = 'cron' ]]; then
    exec crond -f -l 8
elif [[ "$role" = "exec" ]]; then
    exec "$@"
else
    echo "Could not match the container role \"$role\""
    exit 1
fi
