<div class="modal fade" id="create-customer-modal" tabindex="-1" role="dialog" aria-labelledby="create-customer-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="create-customer-modal-label">{{ trans('plugins/car-rentals::booking.create_new_customer') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modal-error-msg" class="alert alert-danger d-none"></div>
                <form id="create-customer-form">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="modal_name" class="form-label">{{ trans('plugins/car-rentals::booking.customer_name') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="modal_name" required placeholder="{{ trans('plugins/car-rentals::booking.customer_name') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="modal_email" class="form-label">{{ trans('plugins/car-rentals::booking.email') }} <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="modal_email" required placeholder="{{ trans('plugins/car-rentals::booking.email_placeholder') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="modal_phone" class="form-label">{{ trans('plugins/car-rentals::booking.phone') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="modal_phone" required placeholder="{{ trans('plugins/car-rentals::booking.phone_placeholder') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="modal_customer_age" class="form-label">{{ trans('plugins/car-rentals::booking.customer_age') }}</label>
                                <input type="number" class="form-control" id="modal_customer_age" min="18">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="modal_address" class="form-label">{{ trans('plugins/car-rentals::booking.address') }}</label>
                                <input type="text" class="form-control" id="modal_address" placeholder="{{ trans('plugins/car-rentals::booking.address_placeholder') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="modal_city" class="form-label">{{ trans('plugins/car-rentals::booking.city') }}</label>
                                <input type="text" class="form-control" id="modal_city" placeholder="{{ trans('plugins/car-rentals::booking.city_placeholder') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="modal_state" class="form-label">{{ trans('plugins/car-rentals::booking.state') }}</label>
                                <input type="text" class="form-control" id="modal_state" placeholder="{{ trans('plugins/car-rentals::booking.state_placeholder') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="modal_country" class="form-label">{{ trans('plugins/car-rentals::booking.country') }}</label>
                                <input type="text" class="form-control" id="modal_country" placeholder="{{ trans('plugins/car-rentals::booking.country_placeholder') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="modal_zip" class="form-label">{{ trans('plugins/car-rentals::booking.zip') }}</label>
                                <input type="text" class="form-control" id="modal_zip" placeholder="{{ trans('plugins/car-rentals::booking.zip_placeholder') }}">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('core/base::forms.cancel') }}</button>
                <button type="button" class="btn btn-primary" id="create-customer-button">{{ trans('plugins/car-rentals::booking.create_new_customer') }}</button>
            </div>
        </div>
    </div>
</div>
