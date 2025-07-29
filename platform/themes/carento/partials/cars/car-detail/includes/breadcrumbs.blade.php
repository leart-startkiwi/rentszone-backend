<div class="box-section box-breadcrumb background-body">
    <div class="container">
        <ul class="breadcrumbs">
            @foreach (Theme::breadcrumb()->getCrumbs() as $crumb)
                @if (! $loop->last)
                    <li>
                        <a href="{{ $crumb['url'] }}" title="{{ $crumb['label'] }}">{{ $crumb['label'] }}</a>
                        <span class="arrow-right">
                            <svg width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 11L6 6L1 1" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </span>
                    </li>

                @else
                    <li><span class="text-breadcrumb">{{ $crumb['label'] }}</span></li>
                @endif
            @endforeach
        </ul>
    </div>
</div>
