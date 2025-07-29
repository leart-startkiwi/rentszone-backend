@if (is_plugin_active('car-rentals') && ($currencies = get_all_currencies()) && $currencies->count() > 1)
    <div class="d-none d-xl-inline-block box-dropdown-cart align-middle head-currency">
        <span class="text-14-medium icon-list icon-cart"><span class="text-14-medium arrow-down">{{ get_application_currency()->title }}</span></span>
        <div class="dropdown-cart">
            <ul>
                @foreach ($currencies as $currency)
                    @continue($currency->getKey() === get_application_currency()->getKey())
                    <li>
                        <a class="text-sm-medium" href="{{ route('public.currency.switch', $currency->title) }}">{{ $currency->title }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

