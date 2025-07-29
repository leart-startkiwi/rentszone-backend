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

        <div class="d-none d-xl-inline-block box-dropdown-cart align-middle mr-15 head-lang language-switcher-wrapper">
            <span class="text-14-medium icon-list icon-account icon-lang">
                @if (Arr::get($options, 'lang_name', true) && ($languageDisplay == 'all' || $languageDisplay == 'name'))
                    &nbsp;<span class="text-14-medium arrow-down">{{ Language::getCurrentLocaleName() }}</span>
                @endif
            </span>
            <div class="dropdown-account">
                <ul>
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
            </div>
        </div>
    @endif
@endif
