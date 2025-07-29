<?php

use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\MultiChecklistFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\FieldOptions\UiSelectorFieldOption;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\MultiCheckListField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\Fields\UiSelectorField;
use Botble\CarRentals\Facades\CarListHelper;
use Botble\CarRentals\Facades\CarRentalsHelper;
use Botble\CarRentals\Forms\Fronts\CheckCarAvailabilityForm;
use Botble\CarRentals\Models\Car;
use Botble\CarRentals\Models\CarAddress;
use Botble\CarRentals\Models\CarCategory;
use Botble\CarRentals\Models\CarFuel;
use Botble\CarRentals\Models\CarMake;
use Botble\CarRentals\Models\CarType;
use Botble\CarRentals\Models\CarView;
use Botble\CarRentals\Models\Service;
use Botble\CarRentals\Repositories\Interfaces\CarInterface;
use Botble\Location\Models\City;
use Botble\Location\Models\State;
use Botble\Shortcode\Compilers\Shortcode as ShortcodeCompiler;
use Botble\Shortcode\Facades\Shortcode;
use Botble\Shortcode\Forms\ShortcodeForm;
use Botble\Theme\Facades\Theme;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;

app()->booted(function (): void {
    if (! is_plugin_active('car-rentals')) {
        return;
    }

    Event::listen(RouteMatched::class, function (): void {
        Shortcode::register(
            'cars',
            __('Cars'),
            __('List of Cars'),
            function (ShortcodeCompiler $shortcode): ?string {
                $style = $shortcode->style;

                if ($style == 'style-lasted') {
                    $style = 'style-latest';
                }

                $style = $style ? (in_array($style, array_keys(get_list_of_car_styles())) ? $style : 'style-feature') : 'style-feature';

                $limit = $shortcode->limit;
                $categoryIds = Shortcode::fields()->getIds('category_ids', $shortcode);

                $carIds = [];
                if ($style === 'style-latest') {
                    $carIds = cache()->remember(
                        key: 'shortcode.cars.car_ids.' . $limit,
                        ttl: now()->addHour(),
                        callback: function () use ($limit) {
                            return CarView::query()
                                ->selectRaw('car_id, count(*) as view_count')
                                ->groupBy('car_id')
                                ->limit($limit)
                                ->latest('view_count')
                                ->pluck('car_id')
                                ->all();
                        }
                    ) ?: [];
                }

                $filterTypes = [];
                $carTypes = [];
                $fuelTypes = [];
                if ($style === 'style-popular') {
                    $filterTypes = explode(',', $shortcode->filter_types);
                    $filterTypes = array_combine($filterTypes, $filterTypes);
                    $carTypes = CarType::query()->get(['id', 'name']);
                    $fuelTypes = CarFuel::query()->get(['id', 'name']);
                }

                $cars = Car::query()
                    ->with(['pickupAddress', 'transmission', 'fuel'])
                    ->withCount('reviews')
                    ->withSum('reviews', 'star')
                    ->when(count($categoryIds), function (Builder $query) use ($categoryIds) {
                        $query->whereHas('categories', function ($query) use ($categoryIds): void {
                            $query->whereIn('cr_car_categories.id', $categoryIds);
                        });
                    })
                    ->when(count($carIds), fn ($builder) => $builder->whereIn('id', $carIds))
                    ->limit($limit)
                    ->get();

                return Theme::partial(
                    'shortcodes.cars.index',
                    compact(
                        'shortcode',
                        'cars',
                        'style',
                        'filterTypes',
                        'carTypes',
                        'fuelTypes',
                    )
                );
            }
        );

        Shortcode::setPreviewImage('cars', Theme::asset()->url('images/shortcodes/cars/style-latest.png'));

        Shortcode::setAdminConfig('cars', function (array $attributes): ShortcodeForm {
            $filterTypes = [
                'category' => __('Category'),
                'fuel_type' => __('Fuel Type'),
                'order' => __('Order'),
                'price_range' => __('Price range'),
            ];

            return ShortcodeForm::createFromArray($attributes)
                ->withLazyLoading()
                ->add(
                    'style',
                    UiSelectorField::class,
                    UiSelectorFieldOption::make()
                        ->choices(
                            collect(get_list_of_car_styles())
                                ->mapWithKeys(fn ($label, $key) => [
                                    ($key) => [
                                        'label' => $label,
                                        'image' => Theme::asset()->url("images/shortcodes/cars/$key.png"),
                                    ],
                                ])
                                ->all()
                        )
                        ->selected(Arr::get($attributes, 'style', 'feature'))
                        ->numberItemsPerRow(3)
                )
                ->add(
                    'category_ids',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Categories'))
                        ->helperText(__('Select categories to display as tabs.'))
                        ->multiple()
                        ->searchable()
                        ->choices(
                            CarCategory::query()
                                ->wherePublished()
                                ->pluck('name', 'id')
                                ->all()
                        )
                        ->selected(explode(',', Arr::get($attributes, 'category_ids')))
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
                    'number_rows',
                    NumberField::class,
                    NumberFieldOption::make()
                        ->label(__('Number of rows'))
                        ->max(5)
                        ->defaultValue(2)
                )
                ->add(
                    'limit',
                    NumberField::class,
                    NumberFieldOption::make()
                        ->label(__('Number of items to display'))
                        ->max(60)
                        ->defaultValue(12)
                )
                ->add(
                    'filter_types',
                    MultiCheckListField::class,
                    MultiChecklistFieldOption::make()
                        ->label(__('Filter Types'))
                        ->choices($filterTypes)
                        ->defaultValue(array_keys($filterTypes))
                )
                ->add(
                    'button_label',
                    TextField::class,
                    TextFieldOption::make()
                        ->label(__('Button Label'))
                        ->placeholder(__('View More'))
                        ->defaultValue(__('View More'))
                )
                ->add(
                    'button_url',
                    TextField::class,
                    TextFieldOption::make()
                        ->label(__('Button URL'))
                        ->placeholder('/contact')
                        ->defaultValue('/')
                );
        });

        Shortcode::register(
            'check-car-availability',
            __('Check Car Availability'),
            __('Check Car Availability'),
            function (ShortcodeCompiler $shortcode): ?string {
                $form = CheckCarAvailabilityForm::create();

                return Theme::partial('shortcodes.check-car-availability.index', compact('shortcode', 'form'));
            }
        );
        Shortcode::setPreviewImage('check-car-availability', Theme::asset()->url('images/ui-blocks/check-car-availability.png'));

        Shortcode::register(
            'car-services',
            __('Car Services'),
            __('List of Car Services'),
            function (ShortcodeCompiler $shortcode): ?string {
                $services = Service::query()
                    ->wherePublished()
                    ->latest()
                    ->wherePublished()
                    ->limit($shortcode->limit ?: 6)
                    ->get();

                return Theme::partial('shortcodes.car-services.index', compact('shortcode', 'services'));
            }
        );
        Shortcode::setPreviewImage('car-services', Theme::asset()->url('images/ui-blocks/car-services.png'));
        Shortcode::setAdminConfig('car-services', function (array $attributes): ShortcodeForm {
            return ShortcodeForm::createFromArray($attributes)
                ->withLazyLoading()
                ->add(
                    'title',
                    TextField::class,
                    TextFieldOption::make()
                        ->label(__('Title'))
                )
                ->add('description', TextareaField::class, DescriptionFieldOption::make())
                ->add(
                    'limit',
                    NumberField::class,
                    NumberFieldOption::make()
                        ->label(__('Limit'))
                        ->helperText(__('Number of items to display'))
                        ->defaultValue(10),
                );
        });

        Shortcode::register('car-list', __('Car List'), __('Car List'), function (ShortcodeCompiler $shortcode): ?string {
            $requestQuery = CarListHelper::getCarFilters(request()->input());

            $sortBy = $requestQuery['sort_by'] ?? 'recently_added';

            /**
             * @var CarInterface $cars
             */
            $cars = app(CarInterface::class)->getCars(
                $requestQuery,
                [
                    'order_by' => $sortBy,
                    'paginate' => [
                        'per_page' => (int) $shortcode->cars_per_page ?: $requestQuery['per_page'],
                        'current_paged' => $requestQuery['page'] ?: 1,
                    ],
                ],
            );

            if (! $shortcode->cars_per_page_options) {
                $perPages = CarListHelper::getPerPageParams();
            } else {
                $perPages = array_map(
                    'trim',
                    array_filter(explode(',', $shortcode->cars_per_page_options), 'is_numeric')
                );
            }

            return Theme::partial(
                'shortcodes.car-list.index',
                compact('shortcode', 'cars', 'perPages')
            );
        });
        Shortcode::setPreviewImage('car-list', Theme::asset()->url('images/ui-blocks/car-list.png'));
        Shortcode::setAdminConfig('car-list', function (array $attributes) {
            return ShortcodeForm::createFromArray($attributes)
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
                ->when(CarRentalsHelper::isEnabledCarFilter(), function (ShortcodeForm $form) {
                    $form->add(
                        'enable_filter',
                        SelectField::class,
                        SelectFieldOption::make()
                            ->label(__('Enable Filter'))
                            ->choices([
                                'no' => __('No'),
                                'yes' => __('Yes'),
                            ])
                            ->helperText(__('When enabled, filter box will show.'))
                    );
                })
                ->add(
                    'default_layout',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Layout'))
                        ->choices([
                            'grid' => __('Grid'),
                            'list' => __('List'),
                        ])
                        ->helperText(__('Select to display grid or list.'))
                )
                ->add(
                    'layout_col',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Layout Column'))
                        ->choices([4 => 3, 3 => 4])
                        ->helperText(__('Select how many car display in 1 row'))
                );
        });

        Shortcode::register(
            'car-loan-form',
            __('Car Loan Form'),
            __('Car Loan Form'),
            function (ShortcodeCompiler $shortcode): ?string {
                Theme::asset()->container('footer')->usePath()->add('car-loan-form', 'js/loan-form.js', ['main-js']);

                return Theme::partial('shortcodes.car-loan-form.index', compact('shortcode'));
            }
        );
        Shortcode::setPreviewImage('car-loan-form', Theme::asset()->url('images/shortcodes/car-loan-form/style-1.png'));
        Shortcode::setAdminConfig('car-loan-form', function (array $attributes): ShortcodeForm {
            return ShortcodeForm::createFromArray($attributes)
                ->withLazyLoading()
                ->add(
                    'style',
                    UiSelectorField::class,
                    UiSelectorFieldOption::make()
                        ->choices(
                            collect(range(1, 3))
                                ->mapWithKeys(fn ($number) => [
                                    ($style = "style-$number") => [
                                        'label' => __('Style :number', ['number' => $number]),
                                        'image' => Theme::asset()->url("images/shortcodes/car-loan-form/$style.png"),
                                    ],
                                ])
                                ->all()
                        )
                        ->selected(Arr::get($attributes, 'style', 'style-1'))
                        ->numberItemsPerRow(3)
                )
                ->add(
                    'title',
                    TextField::class,
                    TextFieldOption::make()
                        ->label(__('Title'))
                )
                ->add('description', TextareaField::class, DescriptionFieldOption::make())
                ->add(
                    'form_url',
                    TextField::class,
                    TextFieldOption::make()
                        ->label(__('Redirect URL'))
                        ->helperText(__('If you want to redirect to another page after submit form. Leave it empty to stay on the same page.'))
                        ->placeholder('/contact')
                )
                ->add('form_title', TextField::class, TextFieldOption::make()->label(__('Form Title')))
                ->add('form_description', TextareaField::class, DescriptionFieldOption::make()->label(__('Form Description')))
                ->add('form_button_label', TextField::class, TextFieldOption::make()->label(__('Form Button Label')))
                ->add('background_image', MediaImageField::class, MediaImageFieldOption::make()->label(__('Background Image')));
        });

        if (is_plugin_active('location')) {
            Shortcode::register('cars-by-locations', __('Cars By Locations'), __('Add Cars By Locations'), function (ShortcodeCompiler $shortcode): ?string {
                $cityIds = Shortcode::fields()->getIds('city_ids', $shortcode);
                $locations = [];
                if (count($cityIds)) {
                    $cities = cache()->remember(
                        'shortcode.cars-by-locations.cities.' . implode('-', $cityIds),
                        Carbon::now()->addHour(),
                        function () use ($cityIds) {
                            $cities = City::query()
                                ->wherePublished()
                                ->whereIn('id', $cityIds)
                                ->select(['id', 'name', 'image', 'slug'])
                                ->oldest('order')
                                ->oldest('name')
                                ->get()
                                ->keyBy('id');

                            $carAddress = CarAddress::query()
                                ->whereIn('city_id', $cityIds)
                                ->get(['id', 'city_id']);

                            $cars = Car::query()
                                ->selectRaw('pick_address_id, count(*) as count_car')
                                ->whereIn('pick_address_id', $carAddress->pluck('id'))
                                ->groupBy('pick_address_id')
                                ->pluck('count_car', 'pick_address_id');

                            $carAddress = $carAddress->groupBy('city_id');
                            $cities->mapWithKeys(function ($item, $key) use ($cars, $carAddress) {
                                $carAddressCurrent = $carAddress[$key] ?? collect();

                                $item->count_cars = $carAddressCurrent->reduce(function ($sum, $carAddressItem) use ($cars) {
                                    $countCar = $cars[$carAddressItem->id] ?? 0;

                                    return $sum + $countCar;
                                }, 0);

                                $item->location_ids = [$item->id];

                                return $item;
                            });

                            return $cities->values()->all();
                        }
                    );

                    $locations = $cities;
                }

                $stateIds = Shortcode::fields()->getIds('state_ids', $shortcode);
                if (count($stateIds)) {
                    $states = cache()->remember(
                        'shortcode.cars-by-locations.states.' . implode('-', $stateIds),
                        Carbon::now()->addHour(),
                        function () use ($stateIds) {
                            $states = State::query()
                                ->wherePublished()
                                ->whereIn('id', $stateIds)
                                ->select(['id', 'name', 'image', 'slug'])
                                ->oldest('order')
                                ->oldest('name')
                                ->with('cities')
                                ->get()
                                ->keyBy('id');

                            $carAddress = CarAddress::query()
                                ->whereIn('state_id', $stateIds)
                                ->get(['id', 'state_id']);

                            $cars = Car::query()
                                ->selectRaw('pick_address_id, count(*) as count_car')
                                ->whereIn('pick_address_id', $carAddress->pluck('id'))
                                ->groupBy('pick_address_id')
                                ->pluck('count_car', 'pick_address_id');

                            $carAddress = $carAddress->groupBy('state_id');
                            $states->mapWithKeys(function ($item, $key) use ($cars, $carAddress) {
                                $carAddressCurrent = $carAddress[$key] ?? collect();

                                $item->count_cars = $carAddressCurrent->reduce(function ($sum, $carAddressItem) use ($cars) {
                                    $countCar = $cars[$carAddressItem->id] ?? 0;

                                    return $sum + $countCar;
                                }, 0);

                                $item->location_ids = $item->cities->pluck('id')->all();

                                return $item;
                            });

                            return $states->values()->all();
                        }
                    );

                    $locations = [...$locations, ...$states];
                }

                return Theme::partial('shortcodes.cars-by-locations.index', compact('shortcode', 'locations'));
            });
            Shortcode::setPreviewImage('cars-by-locations', Theme::asset()->url('images/ui-blocks/cars-by-locations.png'));
            Shortcode::setAdminConfig('cars-by-locations', function (array $attributes) {
                $cities = City::query()
                    ->wherePublished()
                    ->pluck('name', 'id')
                    ->all();

                $states = State::query()
                    ->wherePublished()
                    ->pluck('name', 'id')
                    ->all();

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
                            ->placeholder(__('View More'))
                    )
                    ->add(
                        'button_url',
                        TextField::class,
                        TextFieldOption::make()
                            ->label(__('Button URL'))
                    )
                    ->add(
                        'city_ids',
                        SelectField::class,
                        SelectFieldOption::make()
                            ->label(__('Cities'))
                            ->choices($cities)
                            ->selected(explode(',', Arr::get($attributes, 'city_ids')))
                            ->searchable()
                            ->multiple()
                    )
                    ->add(
                        'state_ids',
                        SelectField::class,
                        SelectFieldOption::make()
                            ->label(__('States'))
                            ->choices($states)
                            ->selected(explode(',', Arr::get($attributes, 'state_ids')))
                            ->searchable()
                            ->multiple()
                    );
            });

            Shortcode::register('brands', __('Brands'), __('List Brands'), function (ShortcodeCompiler $shortcode): ?string {
                $brandIds = Shortcode::fields()->getIds('brand_ids', $shortcode);

                $makes = CarMake::query()
                    ->withCount('cars')
                    ->when(empty($brandIds) === false, fn ($builder) => $builder->whereIn('id', $brandIds))
                    ->get();

                return Theme::partial('shortcodes.brands.index', compact('shortcode', 'makes'));
            });
            Shortcode::setPreviewImage('brands', Theme::asset()->url('images/shortcodes/brands/style-1.png'));
            Shortcode::setAdminConfig('brands', function (array $attributes) {
                return ShortcodeForm::createFromArray($attributes)
                    ->add(
                        'style',
                        UiSelectorField::class,
                        UiSelectorFieldOption::make()
                            ->choices(
                                collect(range(1, 3))
                                    ->mapWithKeys(fn ($number) => [
                                        ("style-$number") => [
                                            'label' => __('Style :number', ['number' => $number]),
                                            'image' => Theme::asset()->url("images/shortcodes/brands/style-$number.png"),
                                        ],
                                    ])
                                    ->all()
                            )
                            ->selected(Arr::get($attributes, 'style', 'style-1'))
                            ->numberItemsPerRow(3)
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
                        'brand_ids',
                        SelectField::class,
                        SelectFieldOption::make()
                            ->searchable()
                            ->multiple()
                            ->choices(CarMake::query()->wherePublished()->pluck('name', 'id')->all())
                            ->selected(explode(',', Arr::get($attributes, 'brand_ids')))
                            ->label(__('Choose brand'))
                    )
                    ->add(
                        'button_label',
                        TextField::class,
                        TextFieldOption::make()
                            ->label(__('Button Label'))
                            ->placeholder(__('Show All Brands'))
                    )
                    ->add(
                        'button_url',
                        TextField::class,
                        TextFieldOption::make()
                            ->label(__('Button URL'))
                    );
            });
        }
    });
});
