#!/usr/bin/env bash
cd "$(dirname $0)/.."

./bin/cleanup.sh

composer install --no-dev

php artisan vue-i18n:generate
php artisan config:cache
php artisan route:cache

yarn install --prod
yarn run prod

cd -
