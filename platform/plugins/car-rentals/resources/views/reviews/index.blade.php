@php
    $review->loadMissing(['car', 'customer']);
    $car = $review->car;
    $customer = $review->customer;
@endphp

<x-core::datagrid class="mb-4">
    @if($car)
        <x-core::datagrid.item :title="trans('plugins/car-rentals::car-rentals.review.forms.car')">
            {{ $car->name }}
        </x-core::datagrid.item>
    @endif

    @if ($customer)
        <x-core::datagrid.item :title="trans('plugins/car-rentals::car-rentals.review.forms.customer')">
            {{ $customer->name }}
        </x-core::datagrid.item>
    @endif

    <x-core::datagrid.item :title="trans('plugins/car-rentals::car-rentals.review.forms.rating')">
        @include('plugins/car-rentals::reviews.partials.rating', ['star' => $review->star])
    </x-core::datagrid.item>
</x-core::datagrid>

<x-core::datagrid>
    <x-core::datagrid.item :title="trans('plugins/car-rentals::car-rentals.review.forms.content')">
    </x-core::datagrid.item>
</x-core::datagrid>

{{ $review->content }}


