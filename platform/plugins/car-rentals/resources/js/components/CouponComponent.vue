<template>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3 position-relative">
                        <label class="form-label">
                            {{ __('coupon.create_coupon_code') }}
                        </label>

                        <div class="input-group input-group-flat">
                            <input
                                type="text"
                                class="form-control coupon-code-input"
                                name="code"
                                :placeholder="__('coupon.enter_coupon_code')"
                                v-model="code"
                            />

                            <span class="input-group-text">
                                <a href="javascript:void(0)" @click="generateCouponCode($event)" class="input-group-link">{{ __('coupon.generate_coupon_code') }}</a>
                            </span>
                        </div>
                    </div>

                    <div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    {{ __('coupon.coupon_type') }}
                                </label>
                                <select
                                    id="discount-type-option"
                                    name="type"
                                    class="form-select"
                                    v-model="type"
                                    @change="handleChangeTypeOption()"
                                >
                                    <option v-for="(coupon_name, coupon_key) in coupon_types" :value="coupon_key">{{ coupon_name }}</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    {{ __('coupon.value') }}
                                </label>
                                <div class="input-group input-group-flat">
                                    <input
                                        type="number"
                                        class="form-control"
                                        name="value"
                                        v-model="coupon_value"
                                        autocomplete="off"
                                        :placeholder="__('coupon.value_placeholder')"
                                    />
                                    <span class="input-group-text">
                                        {{ discountUnit }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="mb-3 position-relative">
                            <label class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_unlimited" v-model="is_unlimited" value="1">
                                <span class="form-check-label">
                                {{ __('coupon.unlimited_coupon') }}
                            </span>
                            </label>
                        </div>

                        <div class="mb-3 position-relative" v-show="!is_unlimited">
                            <label class="form-label">{{ __('coupon.enter_number') }}</label>
                            <input
                                type="number"
                                class="form-control"
                                name="limit"
                                v-model="limit"
                                autocomplete="off"
                                :disabled="is_unlimited"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="meta-boxes card mb-3">
                <div class="card-header">
                    <h4 class="card-title">{{ __('coupon.time') }}</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3 position-relative">
                        <div class="d-flex" style="grid-column-gap: 10px;">
                            <div>
                                <label class="form-label">{{ __('coupon.expires_date') }}</label>
                                <div class="input-icon datepicker">
                                    <input
                                        type="text"
                                        :placeholder="dateFormat"
                                        :data-date-format="dateFormat"
                                        name="end_date"
                                        v-model="end_date"
                                        class="form-control rounded-end-0"
                                        :disabled="is_unlimited_expires"
                                        readonly
                                        data-input
                                    />
                                    <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M11 15h1" /><path d="M12 15v3" /></svg>
                                </span>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">{{ __('coupon.expires_time') }}</label>
                                <div class="input-icon">
                                    <input
                                        type="text"
                                        placeholder="hh:mm"
                                        name="end_time"
                                        v-model="end_time"
                                        class="form-control rounded-start-0 timepicker timepicker-24"
                                        :disabled="is_unlimited_expires"
                                    />
                                    <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-clock" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 7v5l3 3" /></svg>
                                </span>
                                </div>
                            </div>

                            <div class="d-none">
                                <input
                                    name="expires_at"
                                    type="text"
                                    v-model="end_time"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="position-relative">
                        <label class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_unlimited_expires" v-model="is_unlimited_expires" value="1">
                            <span class="form-check-label">{{ __('coupon.never_expired') }}</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary">{{ __('coupon.save') }}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss">
.date-time-group {
    .invalid-feedback {
        position: absolute;
        bottom: -15px;
    }
}
</style>

<script>

const moment = require('moment')

export default {
    data: () => {
        return {
            title: null,
            code: null,
            is_unlimited: true,
            limit: 0,
            is_unlimited_expires: true,
            start_date: moment().format('Y-MM-DD'),
            start_time: '00:00',
            end_date: moment().format('Y-MM-DD'),
            end_time: '23:59',
            type: null,
            coupon_value: null,
            value_label: 'Discount',
            loading: false,
            discountUnit: '$',
            coupon_types: []
        }
    },
    props: {
        currency: {
            type: String,
            default: () => null,
            required: true,
        },
        dateFormat: {
            type: String,
            default: () => 'Y-m-d',
            required: true,
        },
        coupon: {
            type: Object,
            default: () => null,
        }
    },
    mounted: async function () {
        this.discountUnit = this.currency
        this.coupon_types = window.enums.coupon.types;
        this.type = Object.keys(this.coupon_types)[0] || null;
        if (this.coupon) {
            this.code = this.coupon.code

            if (this.coupon.expires_at) {
                this.end_date = moment(this.coupon.expires_at).format('Y-MM-DD')
                this.end_time = moment(this.coupon.expires_at).utc(false).format('HH:mm')
            }
            this.is_unlimited_expires = this.coupon.is_unlimited_expires
            this.is_unlimited = this.coupon.is_unlimited
            this.type = this.coupon.type.value;
            this.coupon_value = this.coupon.value
            this.limit = this.coupon.limit
            this.handleChangeTypeOption()
        }
    },
    methods: {
        generateCouponCode: function (event) {
            event.preventDefault()
            let context = this
            axios
                .post(route('car-rentals.coupons.generate-coupon'))
                .then((res) => {
                    context.code = res.data.data
                    context.title = null
                    $('.coupon-code-input').closest('div').find('.invalid-feedback').remove()
                })
                .catch((res) => {
                    Botble.handleError(res.response.data)
                })
        },
        handleChangeTypeOption: function () {
            let context = this

            context.discountUnit = context.currency
            context.value_label = context.__('coupon.name')

            switch (context.type) {
                case 'amount':
                    break
                case 'percentage':
                    context.discountUnit = '%'
                    break
            }
        }
    }
}
</script>
