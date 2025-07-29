<div id="booking_form_container" class="d-none">
    <div class="mb-3 col-12">
        <hr>
        <div id="car_selection_warning" class="alert alert-warning mb-3">
            <div class="d-flex">
                <div class="me-2">
                    <x-core::icon name="ti ti-alert-triangle" />
                </div>
                <div>
                    {{ trans('plugins/car-rentals::booking.please_select_car') }}
                </div>
            </div>
        </div>
        <div id="selected_car_info" class="d-none">
            <div class="d-flex align-items-center">
                <div class="me-2">
                    <x-core::icon name="ti ti-check" class="text-success" />
                </div>
                <h4 class="mb-0">{{ trans('plugins/car-rentals::booking.selected_car') }}: <span id="selected_car_name"></span></h4>
            </div>
        </div>
    </div>
</div>
