<div class="my-3">
    <x-core::datagrid>
        <x-core::datagrid.item :title="trans('plugins/payment::payment.payer_name')">
            {{ $booking->customer_name }}
        </x-core::datagrid.item>
        <x-core::datagrid.item :title="trans('plugins/payment::payment.email')">
            {{ $booking->customer_email }}
        </x-core::datagrid.item>
        @if ($booking->customer_phone)
            <x-core::datagrid.item :title="trans('plugins/payment::payment.phone')">
                {{ $booking->customer_phone }}
            </x-core::datagrid.item>
        @else
            <x-core::datagrid.item title=""></x-core::datagrid.item>
        @endif
    </x-core::datagrid>
</div>
