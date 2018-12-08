# Blog - Bilingual SPA
[![laravel version](https://img.shields.io/badge/Laravel-5.7-orange.svg)](https://laravel.com/docs/5.7)
[![vue version](https://img.shields.io/badge/Vue.js-2.5-green.svg)](https://vuejs.org)
[![bulma version](https://img.shields.io/badge/Bulma-0.7-brightgreen.svg)](https://bulma.io)

I am a junior developer and this is my first project.
The purpose of this project is to gain practice.

More about me https://piemeram.lv/about

![blog.piemeram.lv](https://raw.githubusercontent.com/narkoze/blog/master/README.png)

## Demo

https://blog.piemeram.lv

## Features

- Authentication [Passport](https://laravel.com/docs/passport)
- Internationalization [Vue I18n](https://kazupon.github.io/vue-i18n/)
- Route management [Vue Router](https://router.vuejs.org)
- Dashboard [Chart.js](https://www.chartjs.org)
- Post editor [TinyMCE](https://www.tiny.cloud)
- Datepicker [vuejs-datepicker](https://github.com/charliekassel/vuejs-datepicker)
- Selects [vue-multiselect](https://github.com/shentao/vue-multiselect)
- Photo viewer [PhotoSwipe](http://photoswipe.com)
- Progressbar [ProgressBar.js](https://kimmobrunfeldt.github.io/progressbar.js/)
- Icons [Font Awesome](https://fontawesome.com)
- Vue.js for frontend
- Laravel for backend
- Bulma for style

## Build and Launch

``` bash
# For production ./bin/build.production.sh
# For development
./bin/build.sh

# Laravel
mv .env.example .env
./artisan key:generate

# Database (make sure you have correct DB_ configurations)
./artisan migrate

# Passport
./artisan passport:install
echo PASSPORT_ENDPOINT=\"\${APP_URL}/oauth/token\" >> .env
```

## License
[MIT](http://opensource.org/licenses/MIT)
