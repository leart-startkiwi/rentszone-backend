<ul {!! $options !!}>
    @foreach ($menu_nodes->loadMissing('metadata') as $key => $row)
        @php
            $hasChild = $row->has_child;
        @endphp

        <li @class(['has-children' => $hasChild])>
            <a href="{{ url($row->url) }}" @if ($row->target !== '_self') target="{{ $row->target }}" @endif>
                {!! $row->icon_html !!}

                {{ $row->title }}

                @if($hasChild)
                    <x-core::icon name="ti ti-chevron-down" size="xs" class="d-none d-xxl-inline-block" />
                @endif
            </a>

            @if ($hasChild)
                {!! Menu::generateMenu(['menu' => $menu, 'menu_nodes' => $row->child, 'view' => 'main-menu', 'options' => ['class' => 'sub-menu']]) !!}
            @endif
        </li>
    @endforeach
</ul>
