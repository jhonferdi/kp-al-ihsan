docker-compose run --rm app php artisan config:clear
docker-compose run --rm app composer install
docker-compose run --rm app php artisan migrate --force
docker-compose run --rm app php artisan db:seed --force
docker-compose run --rm app php artisan config:cache
docker-compose run --rm app php artisan route:cache
docker-compose restart app
