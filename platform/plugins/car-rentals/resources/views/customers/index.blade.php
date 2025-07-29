@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="widget meta-boxes">
                <div class="widget-title">
                    <h4><label class="control-label" for="status">{{ trans('plugins/car-rentals::revenue.view_vendor', ['vendor' => $customer->name]) }}</label></h4>
                </div>
                <div class="widget-body">
                    <div class="row">
                        <div class="col-md-4">
                            <p><strong>{{ trans('plugins/car-rentals::customer.name') }}:</strong> {{ $customer->name }}</p>
                            <p><strong>{{ trans('plugins/car-rentals::customer.email') }}:</strong> <a href="mailto:{{ $customer->email }}">{{ $customer->email }}</a></p>
                            <p><strong>{{ trans('plugins/car-rentals::customer.phone') }}:</strong> <a href="tel:{{ $customer->phone }}">{{ $customer->phone }}</a></p>
                            <p><strong>{{ trans('plugins/car-rentals::customer.balance') }}:</strong> <span class="vendor-balance">{{ format_price($customer->balance) }}</span></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <x-core::button
                                type="button"
                                color="primary"
                                data-bs-toggle="modal"
                                data-bs-target="#update-balance-modal"
                            >
                                {{ trans('plugins/car-rentals::revenue.edit') }}
                            </x-core::button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="widget meta-boxes">
                <div class="widget-title">
                    <h4><label class="control-label" for="status">{{ trans('plugins/car-rentals::revenue.forms.revenue_statistics') }}</label></h4>
                </div>
                <div class="widget-body">
                    <div class="table-responsive">
                        {!! $table->renderTable() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-core::modal
        id="update-balance-modal"
        :title="trans('plugins/car-rentals::revenue.edit')"
        button-id="confirm-update-amount-button"
        :button-label="trans('core/base::forms.update')"
    >
        <form action="{{ route('car-rentals.store.revenue.create', $customer->id) }}" method="post">
            @csrf
            <div class="form-group mb-3">
                <label class="control-label required" for="type">{{ trans('plugins/car-rentals::revenue.forms.type') }}</label>
                {!! Form::customSelect('type', \Botble\CarRentals\Enums\RevenueTypeEnum::adjustLabels(), null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group mb-3">
                <label class="control-label required" for="amount">{{ trans('plugins/car-rentals::revenue.forms.amount') }}</label>
                <input type="number" class="form-control" name="amount" id="amount" placeholder="{{ trans('plugins/car-rentals::revenue.forms.amount_placeholder') }}">
            </div>
            <div class="form-group mb-3">
                <label class="control-label" for="description">{{ trans('core/base::forms.description') }}</label>
                <textarea class="form-control" name="description" id="description" rows="3" placeholder="{{ trans('plugins/car-rentals::revenue.forms.description_placeholder') }}"></textarea>
            </div>
        </form>
    </x-core::modal>
@stop
