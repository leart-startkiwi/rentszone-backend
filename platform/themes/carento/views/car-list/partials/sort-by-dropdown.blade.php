@php
    $sortBy = BaseHelper::stringify(request()->query('sort_by'));

    if(empty($sortBy)) {
        $sortBy = 'recently_added';
    }
@endphp

<span class="text-xs-medium neutral-500 mr-5">{{ __('Sort by') }}:</span>
<div class="dropdown dropdown-sort border-1-right">
    @php($orderByParams = CarListHelper::getSortByParams())
    <button class="btn dropdown-toggle" id="dropdownSort" type="button"
            data-bs-toggle="dropdown" aria-expanded="false"><span>{{ $orderByParams[$sortBy] ?? 'Recently added'}}</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-light m-0"
        aria-labelledby="dropdownSort">
        @foreach($orderByParams as $key => $orderByParam)
            <li>
                <a
                    @class(['dropdown-item dropdown-sort-by', 'active' => BaseHelper::stringify(request()->query('sort_by', Arr::first(array_keys($orderByParams)))) === $key])
                    data-sort-by="{{ $key }}"
                    href="#"
                >
                    {{ $orderByParam }}
                </a>
            </li>
        @endforeach
    </ul>
</div>
