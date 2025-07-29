@if (is_plugin_active('language'))
    @php
        $supportedLocales = Language::getSupportedLocales();

        if (empty($options)) {
            $options = [
                'before' => '',
                'lang_flag' => true,
                'lang_name' => true,
                'class' => '',
                'after' => '',
            ];
        }
    @endphp

    @if ($supportedLocales && count($supportedLocales) > 1)
        @php
            $languageDisplay = setting('language_display', 'all');
            $showRelated = setting('language_show_default_item_if_current_version_not_existed', true);
        @endphp

        <div>{{ __('Language') }}</div>
        <ul class="mobile-menu font-heading" style="margin-top: 10px !important;">
            <li class="has-children">
                <span class="menu-expand"><i class="arrow-small-down"></i></span>
                @if (Arr::get($options, 'lang_name', true) && ($languageDisplay == 'all' || $languageDisplay == 'name'))
                    <strong>{{ Language::getCurrentLocaleName() }}</strong>
                @endif

                <ul class="sub-menu">
                    @foreach ($supportedLocales as $localeCode => $properties)
                        @if ($localeCode != Language::getCurrentLocale())
                            <li>
                                <a class="text-sm-medium" href="{{ $showRelated ? Language::getLocalizedURL($localeCode) : url($localeCode) }}">
                                    @if (Arr::get($options, 'lang_flag', true) && ($languageDisplay == 'all' || $languageDisplay == 'flag'))
                                        {!! language_flag($properties['lang_flag']) !!} <span>{{ $properties['lang_name'] }}</span>
                                    @endif
                                    @if (Arr::get($options, 'lang_name', true) &&  ($languageDisplay == 'name'))
                                        &nbsp;{{ $properties['lang_name'] }}
                                    @endif
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </li>
        </ul>
    @endif
@endif
