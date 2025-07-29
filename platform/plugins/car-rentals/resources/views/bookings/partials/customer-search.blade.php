<div class="mb-3">
    <label for="customer_search" class="form-label">{{ trans('plugins/car-rentals::booking.customer') }}</label>
    <input type="text" id="customer_search" class="form-control" placeholder="{{ trans('plugins/car-rentals::booking.search_customer') }}">
    <div id="customer_search_results" class="dropdown-menu w-100" style="display: none;"></div>
    <button class="btn btn-sm btn-primary mt-2" type="button" id="btn_create_new_customer">
        <x-core::icon name="ti ti-plus" /> {{ trans('plugins/car-rentals::booking.create_new_customer') }}
    </button>
    <div id="selected_customer_info" class="mt-2" style="display: none;"></div>
</div>
@include('plugins/car-rentals::customer-create-modal')
