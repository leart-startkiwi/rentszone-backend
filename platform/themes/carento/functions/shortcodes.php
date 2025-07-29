<?php

use Botble\Base\Forms\FieldOptions\ColorFieldOption;
use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\FieldOptions\UiSelectorFieldOption;
use Botble\Base\Forms\Fields\ColorField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\Fields\UiSelectorField;
use Botble\CarRentals\Facades\CarRentalsHelper;
use Botble\CarRentals\Models\CarAddress;
use Botble\CarRentals\Models\CarType;
use Botble\Shortcode\Compilers\Shortcode as ShortcodeCompiler;
use Botble\Shortcode\Facades\Shortcode;
use Botble\Shortcode\Forms\FieldOptions\ShortcodeTabsFieldOption;
use Botble\Shortcode\Forms\Fields\ShortcodeTabsField;
use Botble\Shortcode\Forms\ShortcodeForm;
use Botble\Theme\Facades\Theme;
use Botble\Theme\Supports\ThemeSupport;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

app()->booted(function (): void {
    ThemeSupport::registerGoogleMapsShortcode();
    ThemeSupport::registerYoutubeShortcode();

    Shortcode::register('branch-locations', __('Brand Locations'), __('Brand Locations'), function (ShortcodeCompiler $shortcode): ?string {
        $tabs = Shortcode::fields()->getTabsData(['name', 'icon_image', 'phone', 'email', 'address'], $shortcode);

        if (! $tabs) {
            return null;
        }

        return Theme::partial('shortcodes.branch-locations.index', compact('shortcode', 'tabs'));
    });
    Shortcode::setPreviewImage('branch-locations', Theme::asset()->url('images/ui-blocks/branch-locations.png'));
    Shortcode::setAdminConfig('branch-locations', function (array $attributes) {
        return ShortcodeForm::createFromArray($attributes)
            ->withLazyLoading()
            ->add(
                'title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Title'))
            )
            ->add(
                'tabs',
                ShortcodeTabsField::class,
                ShortcodeTabsFieldOption::make()
                    ->attrs($attributes)
                    ->fields([
                        'name' => [
                            'title' => __('Name'),
                        ],
                        'icon_image' => [
                            'title' => __('Icon Image'),
                            'type' => 'image',
                        ],
                        'phone' => [
                            'title' => __('Phone number'),
                        ],
                        'email' => [
                            'title' => __('Email'),
                        ],
                        'address' => [
                            'title' => __('Address'),
                            'type' => 'textarea',
                        ],
                    ])
            );
    });

    Shortcode::register('promotion-block', __('Promotion Block'), __('Add Promotion Block'), function (ShortcodeCompiler $shortcode): ?string {
        return Theme::partial('shortcodes.promotion-block.index', compact('shortcode'));
    });
    Shortcode::setPreviewImage('promotion-block', Theme::asset()->url('images/ui-blocks/promotion.png'));
    Shortcode::setAdminConfig('promotion-block', function (array $attributes) {
        return ShortcodeForm::createFromArray($attributes)
            ->withLazyLoading()
            ->add(
                'title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Title'))
            )
            ->add(
                'subtitle',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Subtitle'))
            )
            ->add('button_label', TextField::class, TextFieldOption::make()->label(__('Button Label')))
            ->add('button_url', TextField::class, TextFieldOption::make()->label(__('Button URL')))
            ->add(
                'background_image',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Background Image'))
            );
    });

    Shortcode::register('site-statistics', __('Site Statistics'), __('Site Statistics'), function (ShortcodeCompiler $shortcode): ?string {
        $tabs = Shortcode::fields()->getTabsData(['title', 'data', 'unit'], $shortcode);

        if (! $tabs) {
            return null;
        }

        return Theme::partial('shortcodes.site-statistics.index', compact('shortcode', 'tabs'));
    });

    Shortcode::setAdminConfig('site-statistics', function (array $attributes) {
        return ShortcodeForm::createFromArray($attributes)
            ->withLazyLoading()
            ->add(
                'style',
                UiSelectorField::class,
                UiSelectorFieldOption::make()
                    ->choices(
                        collect(range(1, 2))
                            ->mapWithKeys(fn ($number) => [
                                ($style = "style-$number") => [
                                    'label' => __('Style :number', ['number' => $number]),
                                    'image' => Theme::asset()->url("images/shortcodes/site-statistics/$style.png"),
                                ],
                            ])
                            ->all()
                    )
                    ->selected(Arr::get($attributes, 'style', 'style-1'))
                    ->withoutAspectRatio()
                    ->numberItemsPerRow(1)
            )
            ->add(
                'tabs',
                ShortcodeTabsField::class,
                ShortcodeTabsFieldOption::make()
                    ->attrs($attributes)
                    ->fields([
                        'title' => [
                            'title' => __('Title'),
                        ],
                        'data' => [
                            'title' => __('Data'),
                        ],
                        'unit' => [
                            'title' => __('Unit'),
                        ],
                    ])
            )
            ->add(
                'background_color',
                ColorField::class,
                ColorFieldOption::make()
                    ->label(__('Background color'))
            );
    });
    Shortcode::setPreviewImage('site-statistics', Theme::asset()->url('images/shortcodes/site-statistics/style-1.png'));

    Shortcode::register('pricing', __('Pricing'), __('Pricing'), function (ShortcodeCompiler $shortcode): ?string {
        $tabs = Shortcode::fields()->getTabsData(['name', 'description', 'monthly_price', 'yearly_price', 'features', 'button_label', 'button_url'], $shortcode);

        foreach ($tabs as $key => $item) {
            if ($features = Arr::get($item, 'features')) {
                $tabs[$key]['features'] = collect(explode(PHP_EOL, $features))
                    ->map(function ($feature) {
                        return [
                            'value' => trim($feature, '+- '),
                            'is_available' => str_starts_with($feature, '+') || str_starts_with($feature, ' +'),
                        ];
                    })->all();
            }
        }

        if (! $tabs) {
            return null;
        }

        return Theme::partial('shortcodes.pricing.index', compact('shortcode', 'tabs'));
    });
    Shortcode::setPreviewImage('pricing', Theme::asset()->url('images/ui-blocks/pricing.png'));
    Shortcode::setAdminConfig('pricing', function (array $attributes) {

        $quantity = Arr::get($attributes, 'quantity');

        foreach (range(0, $quantity) as $i) {
            if (Arr::get($attributes, "features_$i")) {
                $attributes["features_$i"] = str_replace(['-', '+'], [PHP_EOL . '-', PHP_EOL . '+'], Arr::get($attributes, "features_$i"));
            }
        }

        return ShortcodeForm::createFromArray($attributes)
            ->withLazyLoading()
            ->add(
                'title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Title'))
            )
            ->add('button_label_monthly', TextField::class, TextFieldOption::make()->label(__('Button Label Monthly')))
            ->add('button_label_yearly', TextField::class, TextFieldOption::make()->label(__('Button Label Yearly')))
            ->add(
                'tabs',
                ShortcodeTabsField::class,
                ShortcodeTabsFieldOption::make()
                    ->attrs($attributes)
                    ->fields([
                        'name' => [
                            'title' => __('Name'),
                        ],
                        'description' => [
                            'title' => __('Description'),
                            'type' => 'textarea',
                        ],
                        'monthly_price' => [
                            'title' => __('Price Monthly'),
                            'type' => 'number',
                        ],
                        'yearly_price' => [
                            'title' => __('Price Yearly'),
                            'type' => 'number',
                        ],
                        'features' => [
                            'title' => __('Features'),
                            'type' => 'textarea',
                            'placeholder' => __('Separate by new line (+ is included, - is not included)'),
                        ],
                        'button_label' => [
                            'title' => __('Button Label'),
                        ],
                        'button_url' => [
                            'title' => __('Button URL'),
                        ],
                    ])
            );
    });

    Shortcode::register('content-columns', __('Content Columns'), __('Content Columns'), function (ShortcodeCompiler $shortcode): ?string {
        $tabs = Shortcode::fields()->getTabsData(['content'], $shortcode);

        if (! $tabs) {
            return null;
        }

        return Theme::partial('shortcodes.content-columns.index', compact('shortcode', 'tabs'));
    });

    Shortcode::setAdminConfig('content-columns', function (array $attributes) {
        return ShortcodeForm::createFromArray($attributes)
            ->withLazyLoading()
            ->add(
                'tabs',
                ShortcodeTabsField::class,
                ShortcodeTabsFieldOption::make()
                    ->attrs($attributes)
                    ->max(2)
                    ->fields([
                        'content' => [
                            'title' => __('Content'),
                            'type' => 'textarea',
                        ],
                    ])
            );
    });
    Shortcode::register('content-images', __('Content Images'), __('Content Images'), function (ShortcodeCompiler $shortcode): ?string {
        $tabs = Shortcode::fields()->getTabsData(['image'], $shortcode);

        if (! $tabs) {
            return null;
        }

        return Theme::partial('shortcodes.content-images.index', compact('shortcode', 'tabs'));
    });

    Shortcode::setAdminConfig('content-images', function (array $attributes) {
        return ShortcodeForm::createFromArray($attributes)
            ->withLazyLoading()
            ->add(
                'tabs',
                ShortcodeTabsField::class,
                ShortcodeTabsFieldOption::make()
                    ->attrs($attributes)
                    ->max(3)
                    ->fields([
                        'image' => [
                            'title' => __('Content'),
                            'type' => 'image',
                        ],
                        'colspan' => [
                            'title' => __('Colspan'),
                            'type' => 'select',
                        ],
                    ])
            );
    });

    Shortcode::register('hero-banners', __('Hero Banners'), __('Add Hero Banners'), function (ShortcodeCompiler $shortcode): ?string {
        $tabs = Shortcode::fields()->getTabsData(['content'], $shortcode);

        return Theme::partial('shortcodes.hero-banners.index', compact('shortcode', 'tabs'));
    });
    Shortcode::setPreviewImage('hero-banners', Theme::asset()->url('images/ui-blocks/hero-banner.png'));
    Shortcode::setAdminConfig('hero-banners', function (array $attributes) {
        return ShortcodeForm::createFromArray($attributes)
            ->add(
                'title',
                TextareaField::class,
                TextareaFieldOption::make()
                    ->label(__('Title'))
            )
            ->add(
                'subtitle',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Subtitle'))
            )
            ->add(
                'key_features',
                ShortcodeTabsField::class,
                ShortcodeTabsFieldOption::make()
                    ->attrs([...$attributes, 'tab_key' => 1])
                    ->fields([
                        'content' => [
                            'title' => __('Content'),
                            'content' => __('Content'),
                        ],
                    ])
                    ->label(__('Key features'))
            )
            ->add(
                'background_image',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Background image'))
            );
    });

    Shortcode::register('car-advance-search', __('Advance Search'), __('Add Car Advance Search'), function (ShortcodeCompiler $shortcode): ?string {
        $locations = CarAddress::query()->with(['city', 'state', 'country'])->get();

        $pickUpLocationDefault = $locations->first();

        $dropOffLocationDefault = $locations->last();
        $pickUpDateDefault = Carbon::now()->format(CarRentalsHelper::getDateFormat());
        $returnDateDefault = Carbon::now()->addDay()->format(CarRentalsHelper::getDateFormat());
        $type = request()->input('adv_type', 'all');
        if (is_array($type)) {
            $type = Arr::first($type);
        }

        if (request()->has('adv_pick_up_location')) {
            $pickUpLocation = $locations->where('id', request()->integer('adv_pick_up_location'))->first();
            $pickUpLocationDefault = $pickUpLocation ?: $pickUpLocationDefault;
        }

        if (request()->has('adv_drop_off_location')) {
            $dropOffLocation = $locations->where('id', request()->integer('adv_drop_off_location'))->first();
            $dropOffLocationDefault = $dropOffLocation ?: $dropOffLocationDefault;
        }

        if (request()->has('adv_pick_up_date')) {
            try {
                $pickUpDate = Carbon::createFromFormat('d M Y', request()->input('adv_pick_up_date'));
            } catch (Exception $exception) {
                report($exception);
                $pickUpDate = null;
            }

            $pickUpDateDefault = $pickUpDate?->format('D, M d Y') ?: $pickUpDateDefault;
        }

        if (request()->has('adv_return_date')) {
            try {
                $returnDate = Carbon::createFromFormat('d M Y', request()->input('adv_return_date'));
            } catch (Exception $exception) {
                report($exception);
                $returnDate = null;
            }

            $returnDateDefault = $returnDate?->format('D, M d Y') ?: $returnDateDefault;
        }

        return Theme::partial(
            view: 'shortcodes.car-advance-search.index',
            args: compact(
                'shortcode',
                'locations',
                'pickUpLocationDefault',
                'dropOffLocationDefault',
                'pickUpDateDefault',
                'returnDateDefault',
                'type'
            )
        );
    });
    Shortcode::setPreviewImage('car-advance-search', Theme::asset()->url('images/ui-blocks/car-advance-search.png'));
    Shortcode::setAdminConfig('car-advance-search', function (array $attributes) {
        $selectedTabs = explode(',', (Arr::get($attributes, 'tabs') ?: 'all,new_car,used_car'));

        return ShortcodeForm::createFromArray($attributes)
            ->columns()
            ->withCustomFields()
            ->add(
                'title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Title'))
                    ->helperText(__('This title will be displayed on the top of the search form if just 1 tab selected.'))
                    ->colspan(2)
            )
            ->add(
                'button_search_name',
                TextField::class,
                TextFieldOption::make()
                    ->defaultValue(__('Find a Vehicle'))
                    ->label(__('Button Search Name'))
                    ->colspan(2)
            )
            ->add(
                'link_need_help',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Need help'))
                    ->placeholder('/faqs')
                    ->colspan(2)
            )
            ->add(
                'top',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(__('Top (px)'))
                    ->colspan(1)
                    ->defaultValue(0)
            )
            ->add(
                'bottom',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(__('Bottom (px)'))
                    ->colspan(1)
                    ->defaultValue(0)
            )
            ->add(
                'left',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(__('Left (px)'))
                    ->colspan(1)
                    ->defaultValue(0)
            )
            ->add(
                'right',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(__('Right (px)'))
                    ->colspan(1)
                    ->defaultValue(0)
            )
            ->add(
                'url',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Redirect URL'))
                    ->defaultValue('/')
                    ->placeholder('/cars')
                    ->colspan(2)
            )
            ->add(
                'background_color',
                ColorField::class,
                ColorFieldOption::make()
                    ->label(__('Background Color'))
                    ->colspan(2),
            )
            ->add(
                'tabs',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(__('Tabs'))
                    ->helperText(__('Select search tabs. Tips: if you want to sort tabs, please add tab one by one, add the first tab then save it, then add the second tab then save it...'))
                    ->searchable()
                    ->multiple()
                    ->choices(collect([
                        'all' => __('All cars'),
                        'new_car' => __('New cars'),
                        'used_car' => __('Used cars'),
                    ])->sortBy(fn ($tab, $key) => array_search($key, $selectedTabs))->all())
                    ->selected($selectedTabs)
                    ->colspan(2)
            );
    });

    Shortcode::register('intro-video', __('Intro Video'), __('Intro Video'), function (ShortcodeCompiler $shortcode): ?string {
        $tabs = Shortcode::fields()->getTabsData(['content'], $shortcode);

        return Theme::partial('shortcodes.intro-video.index', compact('shortcode', 'tabs'));
    });
    Shortcode::setPreviewImage('intro-video', Theme::asset()->url('images/shortcodes/intro-video/style-1.png'));
    Shortcode::setAdminConfig('intro-video', function (array $attributes) {
        return ShortcodeForm::createFromArray($attributes)
            ->withLazyLoading()
            ->add(
                'style',
                UiSelectorField::class,
                UiSelectorFieldOption::make()
                    ->choices(
                        collect(range(1, 2))
                            ->mapWithKeys(fn ($number) => [
                                ($style = "style-$number") => [
                                    'label' => __('Style :number', ['number' => $number]),
                                    'image' => Theme::asset()->url("images/shortcodes/intro-video/$style.png"),
                                ],
                            ])
                            ->all()
                    )
                    ->selected(Arr::get($attributes, 'style', 'style-1'))
                    ->withoutAspectRatio()
                    ->numberItemsPerRow(1)
            )
            ->add(
                'title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Title'))
            )
            ->add(
                'subtitle',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Subtitle'))
            )
            ->add(
                'description',
                TextareaField::class,
                DescriptionFieldOption::make()
            )
            ->add(
                'youtube_video_url',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Video URL'))
                    ->placeholder('https://www.youtube.com/watch?v=xxx')
            )
            ->add(
                'image',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Image'))
            )
            ->add(
                'image_1',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Image :number', ['number' => 1]))
            )
            ->add(
                'list_ticks',
                ShortcodeTabsField::class,
                ShortcodeTabsFieldOption::make()
                    ->attrs([...$attributes, 'tab_key' => 1])
                    ->fields([
                        'content' => [
                            'title' => __('Content'),
                            'content' => __('Content'),
                        ],
                    ])
                    ->label(__('List Tick Green'))
            )
            ->add(
                'button_label',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Button Label'))
            )
            ->add(
                'button_url',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Button URL'))
            );
    });

    Shortcode::register('call-to-action', __('Call To Action'), __('Add Call To Action'), function (ShortcodeCompiler $shortcode): ?string {
        $tabs = Shortcode::fields()->getTabsData(['content'], $shortcode);
        $tabChunk = array_chunk($tabs, 3);

        return Theme::partial('shortcodes.call-to-action.index', compact('shortcode', 'tabs', 'tabChunk'));
    });
    Shortcode::setPreviewImage('call-to-action', Theme::asset()->url('images/shortcodes/intro-video/style-1.png'));
    Shortcode::setAdminConfig('call-to-action', function (array $attributes) {
        return ShortcodeForm::createFromArray($attributes)
            ->add(
                'title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Title'))
            )
            ->add(
                'main_content',
                TextareaField::class,
                TextareaFieldOption::make()
                    ->label(__('Content'))
            )
            ->add(
                'button_label',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Button Label'))
                    ->placeholder(__('Best Car Rental System'))
            )
            ->add(
                'button_url',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Button URL'))
                    ->placeholder('/contact')
            )
            ->add(
                'video_url',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Video URL'))
                    ->placeholder('https://www.youtube.com/watch?v=AOg61RB75Ho')
            )
            ->add(
                'thumbnail_video',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Thumbnail Video'))
            )
            ->add(
                'list_ticks',
                ShortcodeTabsField::class,
                ShortcodeTabsFieldOption::make()
                    ->attrs([...$attributes, 'tab_key' => 1])
                    ->fields([
                        'content' => [
                            'title' => __('Content'),
                            'content' => __('Content'),
                        ],
                    ])
                    ->label(__('List Tick Green'))
            );
    });

    Shortcode::register('car-types', __('Car Type'), __('Add Car Type'), function (ShortcodeCompiler $shortcode): ?string {
        $carTypeIds = Shortcode::fields()->getIds('car_types', $shortcode);
        $carTypes = CarType::query()
            ->withCount('cars')
            ->when(empty($carTypeIds) === false, fn ($builder) => $builder->whereIn('id', $carTypeIds))
            ->get();

        return Theme::partial('shortcodes.car-types.index', compact('shortcode', 'carTypes'));
    });
    Shortcode::setPreviewImage('car-types', Theme::asset()->url('images/shortcodes/car-types/style-1.png'));
    Shortcode::setAdminConfig('car-types', function (array $attributes) {
        return ShortcodeForm::createFromArray($attributes)
            ->withLazyLoading()
            ->add(
                'style',
                UiSelectorField::class,
                UiSelectorFieldOption::make()
                    ->choices(
                        collect(range(1, 2))
                            ->mapWithKeys(fn ($number) => [
                                ("style-$number") => [
                                    'label' => __('Style :number', ['number' => $number]),
                                    'image' => Theme::asset()->url("images/shortcodes/car-types/style-$number.png"),
                                ],
                            ])
                            ->all()
                    )
                    ->selected(Arr::get($attributes, 'style', 'style-1'))
                    ->withoutAspectRatio()
                    ->numberItemsPerRow(1)
            )
            ->add(
                'title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Title'))
            )
            ->add(
                'sub_title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Subtitle'))
            )
            ->add(
                'car_types',
                SelectField::class,
                SelectFieldOption::make()
                    ->searchable()
                    ->multiple()
                    ->choices(CarType::query()->wherePublished()->pluck('name', 'id')->all())
                    ->selected(explode(',', Arr::get($attributes, 'car_types')))
                    ->label(__('Choose Car Type'))
            )
            ->add(
                'redirect_url',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Redirect URL'))
            )
            ->add(
                'button_label',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Button Label'))
            )
            ->add(
                'button_url',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Button URL'))
            );
    });

    Shortcode::register('why-us', __('Why Us'), __('Add Why Us'), function (ShortcodeCompiler $shortcode): ?string {
        $tabs = Shortcode::fields()->getTabsData(['card_image', 'card_title', 'card_content'], $shortcode);

        return Theme::partial('shortcodes.why-us.index', compact('shortcode', 'tabs'));
    });
    Shortcode::setPreviewImage('why-us', Theme::asset()->url('images/ui-blocks/why-us.png'));
    Shortcode::setAdminConfig('why-us', function (array $attributes) {
        return ShortcodeForm::createFromArray($attributes)
            ->add(
                'sub_title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Subtitle'))
            )
            ->add(
                'title',
                TextareaField::class,
                TextareaFieldOption::make()
                    ->label(__('Title'))
            )
            ->add(
                'cards',
                ShortcodeTabsField::class,
                ShortcodeTabsFieldOption::make()
                    ->attrs([...$attributes, 'tab_key' => 1])
                    ->fields([
                        'card_image' => [
                            'title' => __('Card Image'),
                            'type' => 'image',
                        ],
                        'card_title' => [
                            'title' => __('Title'),
                        ],
                        'card_content' => [
                            'title' => __('Content'),
                        ],
                    ])
                    ->label(__('Booking Steps'))
            );
    });

    Shortcode::register('featured-block', __('Featured Block'), __('Add Featured Block'), function (ShortcodeCompiler $shortcode): ?string {
        $tabs = Shortcode::fields()->getTabsData(['content'], $shortcode);

        return Theme::partial('shortcodes.featured-block.index', compact('shortcode', 'tabs'));
    });
    Shortcode::setPreviewImage('featured-block', Theme::asset()->url('images/shortcodes/featured-block/style-1.png'));
    Shortcode::setAdminConfig('featured-block', function (array $attributes) {
        return ShortcodeForm::createFromArray($attributes)
            ->withLazyLoading()
            ->add(
                'style',
                UiSelectorField::class,
                UiSelectorFieldOption::make()
                    ->choices(
                        collect(range(1, 2))
                            ->mapWithKeys(fn ($number) => [
                                ($style = "style-$number") => [
                                    'label' => __('Style :number', ['number' => $number]),
                                    'image' => Theme::asset()->url("images/shortcodes/featured-block/$style.png"),
                                ],
                            ])
                            ->all()
                    )
                    ->selected(Arr::get($attributes, 'style', 'style-1'))
                    ->withoutAspectRatio()
                    ->numberItemsPerRow(1)
            )
            ->add(
                'title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Title'))
            )
            ->add(
                'subtitle',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Subtitle'))
            )
            ->add(
                'description',
                TextareaField::class,
                DescriptionFieldOption::make()
            )
            ->add(
                'button_label',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Button Label'))
                    ->placeholder(__('Get Started Now'))
            )
            ->add(
                'button_url',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Button URL'))
            )
            ->add(
                'list_ticks',
                ShortcodeTabsField::class,
                ShortcodeTabsFieldOption::make()
                    ->attrs([...$attributes, 'tab_key' => 1])
                    ->fields([
                        'content' => [
                            'title' => __('Content'),
                        ],
                    ])
                    ->label(__('List Tick Green'))
            )
            ->add(
                'image_1',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Image :number', ['number' => 1]))
            )
            ->add(
                'image_2',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Image :number', ['number' => 2]))
            )
            ->add(
                'image_3',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Image :number', ['number' => 3]))
            )
            ->add(
                'image_4',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Image :number', ['number' => 4]))
            )
            ->add(
                'image_5',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Image :number', ['number' => 5]))
            );
    });

    Shortcode::register('trusted-expertise', __('Trusted Expertise'), __('Add Trusted Expertise'), function (ShortcodeCompiler $shortcode): ?string {
        $tabs = Shortcode::fields()->getTabsData(['content'], $shortcode);

        return Theme::partial('shortcodes.trusted-expertise.index', compact('shortcode', 'tabs'));
    });
    Shortcode::setPreviewImage('trusted-expertise', Theme::asset()->url('images/shortcodes/featured-block/style-1.png'));
    Shortcode::setAdminConfig('trusted-expertise', function (array $attributes) {
        return ShortcodeForm::createFromArray($attributes)
            ->add(
                'title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Title'))
            )
            ->add(
                'main_content',
                TextareaField::class,
                TextareaFieldOption::make()
                    ->label(__('Content'))
            )
            ->add(
                'button_label',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Button Label'))
                    ->placeholder(__('Get Started Now'))
            )
            ->add(
                'button_url',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Button URL'))
                    ->placeholder('/contact')
            )
            ->add(
                'list_ticks',
                ShortcodeTabsField::class,
                ShortcodeTabsFieldOption::make()
                    ->attrs([...$attributes, 'tab_key' => 1])
                    ->fields([
                        'content' => [
                            'title' => __('Content'),
                            'content' => __('Content'),
                        ],
                    ])
                    ->label(__('List Tick Green'))
            )
            ->add(
                'image_1',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Image 1 (184 × 339)'))
            )
            ->add(
                'image_2',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Image 2 (184 × 289)'))
            )
            ->add(
                'image_3',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Image 3 (184 × 188)'))
            )
            ->add(
                'image_4',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Image 4 (184 × 186)'))
            )
            ->add(
                'image_5',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Image 5 (184 × 290)'))
            );
    });

    Shortcode::register('about-us-information', __('About Us Information'), __('About Us Information'), function (ShortcodeCompiler $shortcode): ?string {
        $tabs = Shortcode::fields()->getTabsData(['data_number', 'data_title', 'image'], $shortcode);

        return Theme::partial('shortcodes.about-us-information.index', compact('shortcode', 'tabs'));
    });
    Shortcode::setPreviewImage('about-us-information', Theme::asset()->url('images/ui-blocks/about-us-info.png'));
    Shortcode::setAdminConfig('about-us-information', function (array $attributes) {
        return ShortcodeForm::createFromArray($attributes)
            ->add(
                'title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Title'))
            )
            ->add(
                'description',
                TextareaField::class,
                DescriptionFieldOption::make()
            )
            ->add(
                'tabs',
                ShortcodeTabsField::class,
                ShortcodeTabsFieldOption::make()
                    ->attrs($attributes)
                    ->fields([
                        'data_number' => [
                            'title' => __('Data Number'),
                        ],
                        'data_title' => [
                            'title' => __('Data Title'),
                        ],
                        'image' => [
                            'title' => __('Image'),
                            'type' => 'image',
                        ],
                    ])
            );
    });

    Shortcode::register('simple-banners', __('Simple Banner'), __('Add Simple Banner'), function (ShortcodeCompiler $shortcode): ?string {
        $banners = Shortcode::fields()->getTabsData([
            'title',
            'subtitle',
            'image',
            'button_url',
            'button_name',
            'button_color',
            'background_color',
        ], $shortcode);

        return Theme::partial('shortcodes.simple-banners.index', compact('shortcode', 'banners'));
    });
    Shortcode::setPreviewImage('simple-banners', Theme::asset()->url('images/ui-blocks/simple-banners.png'));
    Shortcode::setAdminConfig('simple-banners', function (array $attributes) {
        return ShortcodeForm::createFromArray($attributes)
            ->add(
                'banners',
                ShortcodeTabsField::class,
                ShortcodeTabsFieldOption::make()
                    ->attrs([...$attributes, 'tab_key' => 1, 'quantity' => 2])
                    ->fields([
                        'title' => [
                            'title' => __('Title'),
                        ],
                        'subtitle' => [
                            'title' => __('Subtitle'),
                            'type' => 'textarea',
                        ],
                        'image' => [
                            'title' => __('Image'),
                            'type' => 'image',
                        ],
                        'button_url' => [
                            'title' => __('Button URL'),
                        ],
                        'button_name' => [
                            'title' => __('Button Name'),
                        ],
                        'button_color' => [
                            'title' => __('Button Color'),
                            'type' => 'color',
                        ],
                        'background_color' => [
                            'title' => __('Background Color'),
                            'type' => 'color',
                        ],
                    ])
            );
    });

    Shortcode::register('install-apps', __('Install Apps'), __('Add Install Apps'), function (ShortcodeCompiler $shortcode): ?string {
        return Theme::partial('shortcodes.install-apps.index', compact('shortcode'));
    });
    Shortcode::setPreviewImage('install-apps', Theme::asset()->url('images/shortcodes/install-apps/style-1.png'));
    Shortcode::setAdminConfig('install-apps', function (array $attributes) {
        return ShortcodeForm::createFromArray($attributes)
            ->add(
                'style',
                UiSelectorField::class,
                UiSelectorFieldOption::make()
                    ->choices(
                        collect(range(1, 3))
                            ->mapWithKeys(fn ($number) => [
                                ($style = "style-$number") => [
                                    'label' => __('Style :number', ['number' => $number]),
                                    'image' => Theme::asset()->url("images/shortcodes/install-apps/$style.png"),
                                ],
                            ])
                            ->all()
                    )
                    ->selected(Arr::get($attributes, 'style', 'style-1'))
                    ->withoutAspectRatio()
                    ->numberItemsPerRow(1)
            )
            ->add(
                'title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Title'))
            )
            ->add(
                'description',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Description'))
            )
            ->add(
                'android_app_url',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Android app URL'))
            )
            ->add(
                'android_app_image',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Android app image'))
            )
            ->add(
                'ios_app_url',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('IOS app URL'))
            )
            ->add(
                'ios_app_image',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('IOS app image'))
            )
            ->add(
                'button_label',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Button Label'))
                    ->placeholder(__('Download Apps'))
            )
            ->add(
                'button_url',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Button URL'))
                    ->defaultValue('/')
            )
            ->add(
                'decor_image',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Decor image'))
            )
            ->add(
                'background_image',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Background Image'))
            )
            ->add(
                'background_color',
                ColorField::class,
                ColorFieldOption::make()
                    ->label(__('Background color'))
            );
    });

    Shortcode::register('banner', __('Banner'), __('Banner'), function (ShortcodeCompiler $shortcode): ?string {
        return Theme::partial('shortcodes.banner.index', compact('shortcode'));
    });
    Shortcode::setPreviewImage('banner', Theme::asset()->url('images/ui-blocks/banner.png'));
    Shortcode::setAdminConfig('banner', function (array $attributes) {
        return ShortcodeForm::createFromArray($attributes)
            ->withLazyLoading()
            ->add(
                'tag',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Tag'))
                    ->helperText(__('if you want highlight text, wrap it in a &lt;span&gt; tag. Ex: &lt;span&gt; highlight text &lt;/span&gt;'))
            )
            ->add(
                'title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Title'))
            )
            ->add(
                'subtitle',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Sub Title'))
            )
            ->add(
                'background_image',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Background image'))
            );
    });

    Shortcode::register('rental-invitations', __('Rental Invitations'), __('Add Rental Invitations'), function (ShortcodeCompiler $shortcode): ?string {
        $cards = Shortcode::fields()->getTabsData(['icon', 'title', 'subtitle', 'button_url', 'button_name'], $shortcode);

        return Theme::partial('shortcodes.rental-invitations.index', compact('shortcode', 'cards'));
    });
    Shortcode::setPreviewImage('rental-invitations', Theme::asset()->url('images/ui-blocks/rental-invitations.png'));
    Shortcode::setAdminConfig('rental-invitations', function (array $attributes) {
        return ShortcodeForm::createFromArray($attributes)
            ->add(
                'title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Title'))
            )
            ->add(
                'description',
                TextareaField::class,
                TextareaFieldOption::make()
                    ->label(__('Description'))
            )
            ->add(
                'middle_image',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Middle Image'))
            )
            ->add(
                'cards',
                ShortcodeTabsField::class,
                ShortcodeTabsFieldOption::make()
                    ->attrs([...$attributes, 'tab_key' => 1, 'quantity' => 2])
                    ->fields([
                        'icon' => [
                            'title' => __('Icon'),
                            'type' => 'image',
                        ],
                        'title' => [
                            'title' => __('Title'),
                        ],
                        'subtitle' => [
                            'title' => __('Subtitle'),
                            'type' => 'textarea',
                        ],
                        'button_url' => [
                            'title' => __('Button URL'),
                        ],
                        'button_name' => [
                            'title' => __('Button Name'),
                        ],
                    ])
            )
            ->add(
                'background_image',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Background Image'))
            );
    });

    if (is_plugin_active('simple-slider')) {
        add_filter(SIMPLE_SLIDER_VIEW_TEMPLATE, function () {
            return Theme::getThemeNamespace() . '::partials.shortcodes.sliders.main';
        }, 120);
        Shortcode::setPreviewImage('simple-slider', Theme::asset()->url('images/shortcodes/simple-sliders/style-1.png'));
        Shortcode::modifyAdminConfig('simple-slider', function (ShortcodeForm $form) {
            $attributes = is_array($form->getModel()) ? $form->getModel() : [];

            return $form
                ->addBefore(
                    'key',
                    'style',
                    UiSelectorField::class,
                    UiSelectorFieldOption::make()
                        ->choices(
                            collect(range(1, 3))
                                ->mapWithKeys(fn ($number) => [
                                    ($style = "style-$number") => [
                                        'label' => __('Style :number', ['number' => $number]),
                                        'image' => Theme::asset()->url("images/shortcodes/simple-sliders/$style.png"),
                                    ],
                                ])
                                ->all()
                        )
                        ->selected(Arr::get($attributes, 'style', 'style-1'))
                        ->withoutAspectRatio()
                        ->numberItemsPerRow(1)
                );
        });
    }
});
