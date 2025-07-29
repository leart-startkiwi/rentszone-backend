<div class="swatches-container">
    <div class="header">
        <div class="swatch-item">
            {{ trans('plugins/car-rentals::currency.name') }}
        </div>
        <div class="swatch-item">
            {{ trans('plugins/car-rentals::currency.symbol') }}
        </div>
        <div class="swatch-item swatch-decimals">
            {{ trans('plugins/car-rentals::currency.number_of_decimals') }}
        </div>
        <div class="swatch-item swatch-exchange-rate">
            {{ trans('plugins/car-rentals::currency.exchange_rate') }}
        </div>
        <div class="swatch-item swatch-is-prefix-symbol">
            {{ trans('plugins/car-rentals::currency.is_prefix_symbol') }}
        </div>
        <div class="swatch-is-default">
            {{ trans('plugins/car-rentals::currency.is_default') }}
        </div>
        <div class="remove-item">{{ trans('plugins/car-rentals::currency.remove') }}</div>
    </div>

    <ul class="swatches-list"></ul>

    <div class="d-flex justify-content-between w-100 align-items-center">
        <a class="js-add-new-attribute" href="javascript:void(0)">
            {{ trans('plugins/car-rentals::currency.new_currency') }}
        </a>
        <x-core::form.helper-text>
            {{ trans('plugins/car-rentals::currency.instruction') }}
        </x-core::form.helper-text>
    </div>
</div>
