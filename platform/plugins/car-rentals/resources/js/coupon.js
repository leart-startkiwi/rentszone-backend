import CouponComponent from './components/CouponComponent.vue'

if (typeof vueApp !== 'undefined') {
    vueApp.booting((vue) => {
        vue.component('coupon-component', CouponComponent)
    })
}
