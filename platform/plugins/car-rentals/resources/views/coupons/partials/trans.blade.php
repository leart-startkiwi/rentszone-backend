<script>
    'use strict';

    window.trans = window.trans || {};

    window.trans.coupon = {
        create_coupon_code: '{{ trans('plugins/car-rentals::coupon.create_coupon_code') }}',
        enter_coupon_code: '{{ trans('plugins/car-rentals::coupon.enter_coupon_code') }}',
        generate_coupon_code: '{{ trans('plugins/car-rentals::coupon.generate_coupon_code') }}',
        coupon_type: '{{ trans('plugins/car-rentals::coupon.coupon_type') }}',
        percentage_coupon: '{{ trans('plugins/car-rentals::coupon.percentage_coupon') }}',
        value: '{{ trans('plugins/car-rentals::coupon.value') }}',
        value_placeholder: '{{ trans('plugins/car-rentals::coupon.value_placeholder') }}',
        unlimited_coupon: '{{ trans('plugins/car-rentals::coupon.unlimited_coupon') }}',
        enter_number: '{{ trans('plugins/car-rentals::coupon.enter_number') }}',
        time: '{{ trans('plugins/car-rentals::coupon.time') }}',
        expires_date: '{{ trans('plugins/car-rentals::coupon.expires_date') }}',
        expires_time: '{{ trans('plugins/car-rentals::coupon.expires_time') }}',
        never_expired: '{{ trans('plugins/car-rentals::coupon.never_expired') }}',
        save: '{{ trans('plugins/car-rentals::coupon.save') }}',
        name: '{{ trans('plugins/car-rentals::coupon.name') }}',
    }

    $(document).ready(function() {
        $(document).on('click', 'body', function(e) {
            let container = $('.box-search-advance');

            if (!container.is(e.target) && container.has(e.target).length === 0) {
                container.find('.panel').addClass('hidden');
            }
        });
    });
</script>
