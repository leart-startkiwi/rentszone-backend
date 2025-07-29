let mix = require('laravel-mix')

const path = require('path')
let directory = path.basename(path.resolve(__dirname))

const source = 'platform/themes/' + directory
const dist = 'public/themes/' + directory

mix
    .sass(source + '/assets/sass/style.scss', dist + '/css')
    .js(source + '/assets/js/main.js', dist + '/js')
    .js(source + '/assets/js/custom.js', dist + '/js')
    .js(source + '/assets/js/loan-form.js', dist + '/js')
    .js(source + '/assets/js/dark.js', dist + '/js')

if (mix.inProduction()) {
    mix
        .copy(dist + '/css/style.css', source + '/public/css')
        .copy(dist + '/js/main.js', source + '/public/js')
        .copy(dist + '/js/custom.js', source + '/public/js')
        .copy(dist + '/js/loan-form.js', source + '/public/js')
        .copy(dist + '/js/dark.js', source + '/public/js')
}
