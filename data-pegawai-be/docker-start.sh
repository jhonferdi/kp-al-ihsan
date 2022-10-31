#!/usr/bin/env bash

set -e

role=${CONTAINER_ROLE:-app}
env=${APP_ENV:-production}

# if [ "$env" != "local" ]; then
#     echo "Caching configuration..."
#     (cd /var/www && php artisan config:cache && php artisan route:cache && php artisan view:cache)
# fi

if [ "$role" = "app" ]; then

    exec php-fpm
    
elif [ "$role" = "cron" ]; then

    while [ true ]
    do
          now=$(date +"%Y-%m-%d %T")
          echo "[$now] Executing cron..."
      php /var/www/artisan schedule:run --verbose --no-interaction &
      sleep $((60 - $(date +%s) % 60))
    done

else
    echo "Could not match the container role \"$role\""
    exit 1
fi
