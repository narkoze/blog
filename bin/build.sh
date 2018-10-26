#!/usr/bin/env bash
cd "$(dirname $0)/.."
./bin/cleanup.sh

composer install

php artisan vue-i18n:generate

yarn
yarn run dev

cd -
