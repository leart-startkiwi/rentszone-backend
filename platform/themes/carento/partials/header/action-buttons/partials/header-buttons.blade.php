@if ($data = theme_option('header_action_buttons'))
    @php
        $data = json_decode($data, true);
    @endphp

    @foreach($data as $item)
        @php
            $item = collect($item)->pluck('value', 'key');
            $label = $item->get('label');
            $url = $item->get('url');
        @endphp

        @if ($label && $url)
            <a href="{{ $url }}" @class(['btn btn-signin background-brand-2 text-dark', $wrapperClass ?? null])>
                {!! BaseHelper::clean($label) !!}
            </a>
        @endif
    @endforeach
@endif
