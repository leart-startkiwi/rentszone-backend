@php
    $fields = Botble\CarRentals\Enums\PayoutPaymentMethodsEnum::getFields($paymentChannel);
@endphp

@if($fields)
    <x-core::form.fieldset class="mb-3">
        <h4>{{ $title ?? __('You will receive money through the information below') }}</h4>

        <x-core::datagrid>
            @foreach ($fields as $key => $field)
                @if (Arr::get($bankInfo, $key))
                    <x-core::datagrid.item>
                        <x-slot:title>{{ Arr::get($field, 'title') }}</x-slot:title>
                        {{ Arr::get($bankInfo, $key) }}
                    </x-core::datagrid.item>
                @endif
            @endforeach
        </x-core::datagrid>
    </x-core::form.fieldset>

    @isset($link)
        <p class="mb-3">{!! BaseHelper::clean(__('You can change it <a href=":link">here</a>', ['link' => $link])) !!}.</p>
    @endisset
@endif

{!! apply_filters('car_rentals_withdrawal_payout_info', null) !!}
