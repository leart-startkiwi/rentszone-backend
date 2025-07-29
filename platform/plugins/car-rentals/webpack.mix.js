const mix = require('laravel-mix');
const path = require('path');

const directory = path.basename(path.resolve(__dirname));
const source = `platform/plugins/${directory}`;
const dist = `public/vendor/core/plugins/${directory}`;

mix
    .sass(`${source}/resources/sass/review.scss`, `${dist}/css`)
    .sass(`${source}/resources/sass/car-rentals.scss`, `${dist}/css`)
    .sass(`${source}/resources/sass/front-theme.scss`, `${dist}/css`)
    .sass(`${source}/resources/sass/front-booking-form.scss`, `${dist}/css`)
    .sass(`${source}/resources/sass/front-theme-rtl.scss`, `${dist}/css`)
    .sass(`${source}/resources/sass/front-auth.scss`, `${dist}/css`)
    .sass(`${source}/resources/sass/customer.scss`, `${dist}/css`)
    .sass(`${source}/resources/sass/customer-rtl.scss`, `${dist}/css`)
    .sass(`${source}/resources/sass/vendor-dashboard/dashboard.scss`, `${dist}/css`)
    .sass(`${source}/resources/sass/vendor-dashboard/dashboard-rtl.scss`, `${dist}/css`)
    .js(`${source}/resources/js/coupon.js`, `${dist}/js`)
    .js(`${source}/resources/js/report.js`, `${dist}/js`)
    .js(`${source}/resources/js/booking-reports.js`, `${dist}/js`)
    .js(`${source}/resources/js/checkout.js`, `${dist}/js`)
    .js(`${source}/resources/js/car-form.js`, `${dist}/js`)
    .js(`${source}/resources/js/front-booking-form.js`, `${dist}/js`)
    .js(`${source}/resources/js/avatar.js`, `${dist}/js`)
    .js(`${source}/resources/js/vendor-dashboard/dashboard.js`, `${dist}/js`)
    .js(`${source}/resources/js/vendor-dashboard/dashboard-vendor.js`, `${dist}/js`)
    .js(`${source}/resources/js/commission-setting.js`, `${dist}/js`)
    .js(`${source}/resources/js/booking-create.js`, `${dist}/js`)
    .js(`${source}/resources/js/booking-car-search.js`, `${dist}/js`)
    .js(`${source}/resources/js/customer-autocomplete.js`, `${dist}/js`)

if (mix.inProduction()) {
    mix
        .copy(`${dist}/css/review.css`, `${source}/public/css`)
        .copy(`${dist}/css/car-rentals.css`, `${source}/public/css`)
        .copy(`${dist}/css/front-theme.css`, `${source}/public/css`)
        .copy(`${dist}/css/front-booking-form.css`, `${source}/public/css`)
        .copy(`${dist}/css/front-theme-rtl.css`, `${source}/public/css`)
        .copy(`${dist}/css/front-auth.css`, `${source}/public/css`)
        .copy(`${dist}/css/customer.css`, `${source}/public/css`)
        .copy(`${dist}/css/customer-rtl.css`, `${source}/public/css`)
        .copy(`${dist}/css/dashboard.css`, `${source}/public/css`)
        .copy(`${dist}/css/dashboard-rtl.css`, `${source}/public/css`)
        .copy(`${dist}/js/coupon.js`, `${source}/public/js`)
        .copy(`${dist}/js/report.js`, `${source}/public/js`)
        .copy(`${dist}/js/booking-reports.js`, `${source}/public/js`)
        .copy(`${dist}/js/checkout.js`, `${source}/public/js`)
        .copy(`${dist}/js/car-form.js`, `${source}/public/js`)
        .copy(`${dist}/js/front-booking-form.js`, `${source}/public/js`)
        .copy(`${dist}/js/avatar.js`, `${source}/public/js`)
        .copy(`${dist}/js/dashboard.js`, `${source}/public/js`)
        .copy(`${dist}/js/dashboard-vendor.js`, `${source}/public/js`)
        .copy(`${dist}/js/commission-setting.js`, `${source}/public/js`)
        .copy(`${dist}/js/booking-create.js`, `${source}/public/js`)
        .copy(`${dist}/js/booking-car-search.js`, `${source}/public/js`)
        .copy(`${dist}/js/customer-autocomplete.js`, `${source}/public/js`)
}
