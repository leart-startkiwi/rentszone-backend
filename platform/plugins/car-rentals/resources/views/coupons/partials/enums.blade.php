<script>
    'use strict';

    window.enums = window.enums || {};

    window.enums.coupon = {
        types: {!! json_encode(\Botble\CarRentals\Enums\CouponTypeEnum::labels()) !!},
    }
</script>
