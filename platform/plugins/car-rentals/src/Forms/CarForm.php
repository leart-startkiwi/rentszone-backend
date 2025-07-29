<?php

namespace Botble\CarRentals\Forms;

use Botble\Base\Facades\Assets;
use Botble\Base\Forms\FieldOptions\AutocompleteFieldOption;
use Botble\Base\Forms\FieldOptions\CheckboxFieldOption;
use Botble\Base\Forms\FieldOptions\ContentFieldOption;
use Botble\Base\Forms\FieldOptions\HtmlFieldOption;
use Botble\Base\Forms\FieldOptions\IsFeaturedFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\FieldOptions\TagFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\AutocompleteField;
use Botble\Base\Forms\Fields\EditorField;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\MediaImagesField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TagField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\Fields\TreeCategoryField;
use Botble\Base\Forms\FormAbstract;
use Botble\Base\Forms\FormFieldOptions;
use Botble\CarRentals\Enums\CarConditionEnum;
use Botble\CarRentals\Enums\CarForSaleStatusEnum;
use Botble\CarRentals\Enums\CarRentalTypeEnum;
use Botble\CarRentals\Enums\CarStatusEnum;
use Botble\CarRentals\Facades\CarRentalsHelper;
use Botble\CarRentals\Http\Requests\CarRequest;
use Botble\CarRentals\Models\Car;
use Botble\CarRentals\Models\CarAddress;
use Botble\CarRentals\Models\CarAmenity;
use Botble\CarRentals\Models\CarCategory;
use Botble\CarRentals\Models\CarColor;
use Botble\CarRentals\Models\CarFuel;
use Botble\CarRentals\Models\CarMake;
use Botble\CarRentals\Models\CarTag;
use Botble\CarRentals\Models\CarTransmission;
use Botble\CarRentals\Models\CarType;
use Botble\CarRentals\Models\Tax;
use Botble\CarRentals\Services\GetCategoriesWithChildrenService;
use Illuminate\Database\Eloquent\Model;

class CarForm extends FormAbstract
{
    public function setup(): void
    {
        Assets::addScriptsDirectly('vendor/core/plugins/car-rentals/js/car-form.js')
            ->addScripts(['input-mask']);

        $model = $this->getModel();
        $isSameDropOff = true;
        if ($model instanceof Model) {
            $isSameDropOff = ! $model->getAttribute('return_address_id') || $model->getAttribute('pick_address_id') == $model->getAttribute('return_address_id');
        }

        $carTags = CarTag::query()->wherePublished()->pluck('name', 'id')->all();
        $selectedTags = [];

        if ($carTags && $model) {
            $selectedTags = $model->tags->pluck('id')->all();
        }

        /**
         * @var GetCategoriesWithChildrenService $getCategoriesWithChildrenService
         */
        $getCategoriesWithChildrenService = app(GetCategoriesWithChildrenService::class);
        $categoriesWithChildren = $getCategoriesWithChildrenService->execute();

        $addressOptions = CarAddress::query()
            ->select(['id', 'detail_address', 'city_id', 'state_id', 'country_id'])
            ->wherePublished()
            ->get()
            ->pluck('full_address', 'id')
            ->all();

        $carColors = CarColor::query()->select(['id', 'name'])->wherePublished()->pluck('name', 'id')->all();
        $selectedColors = [];

        if ($carColors && $model) {
            $selectedColors = $model->colors->pluck('id')->all();
        }

        $carAmenities = CarAmenity::query()->wherePublished()->pluck('name', 'id')->all();
        $selectedAmenities = [];

        if ($carAmenities && $model) {
            $selectedAmenities = $model->amenities->pluck('id')->all();
        }

        $taxes = Tax::query()
            ->selectRaw('CONCAT(name, " (", FORMAT(percentage, 2), "%)") as name, id')
            ->wherePublished()
            ->pluck('name', 'id')
            ->all();

        $carPurposes = [];

        if (CarRentalsHelper::isRentalBookingEnabled()) {
            $carPurposes['rent'] = trans('plugins/car-rentals::car-rentals.car.forms.for_rent');
        }
        if (get_car_rentals_setting('enabled_car_sale', true)) {
            $carPurposes['sale'] = trans('plugins/car-rentals::car-rentals.car.forms.for_sale');
        }

        $carPurpose = old('car_purpose', $this->getModel() && $this->getModel()->is_for_sale ? 'sale' : 'rent');

        if ($carPurpose == 'rent' && ! CarRentalsHelper::isRentalBookingEnabled()) {
            $carPurpose = 'sale';
        }

        if ($carPurpose == 'sale' && ! get_car_rentals_setting('enabled_car_sale', true)) {
            if (CarRentalsHelper::isRentalBookingEnabled()) {
                $carPurpose = 'rent';
            } else {
                $carPurpose = null;
            }
        }

        $this
            ->model(Car::class)
            ->setValidatorClass(CarRequest::class)
            ->withCustomFields()
            ->columns()
            ->template('plugins/car-rentals::cars.form')
            ->add(
                'name',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/car-rentals::car-rentals.car.forms.name'))
                    ->colspan(2)
                    ->required()
                    ->placeholder(trans('plugins/car-rentals::car-rentals.car.placeholders.name'))
            )
            ->add(
                'description',
                TextareaField::class,
                TextareaFieldOption::make()
                    ->label(trans('plugins/car-rentals::car-rentals.car.forms.description'))
                    ->colspan(2)
                    ->placeholder(trans('plugins/car-rentals::car-rentals.car.placeholders.description'))
            )
            ->add('content', EditorField::class, ContentFieldOption::make()->colspan(2))
            ->add(
                'location',
                TextField::class,
                TextFieldOption::make()
                    ->colspan(2)
                    ->label(trans('plugins/car-rentals::car-rentals.car.forms.location'))
                    ->placeholder(trans('plugins/car-rentals::car-rentals.car.placeholders.location'))
            )
            ->add(
                'car_type_divider',
                HtmlField::class,
                HtmlFieldOption::make()
                    ->content('')
                    ->colspan(2)
            )
            ->when(count($carPurposes) === 2, function () use ($carPurposes, $carPurpose) {
                $this
                    ->add(
                        'car_purpose',
                        SelectField::class,
                        SelectFieldOption::make()
                            ->label(trans('plugins/car-rentals::car-rentals.car.forms.car_purpose'))
                            ->choices($carPurposes)
                            ->defaultValue($carPurpose)
                            ->selected($carPurpose)
                    );
            })
            ->addOpenCollapsible('car_purpose', 'rent', $carPurpose, attributes: ['class' => 'form-fieldset col-lg-12'])
            ->add(
                'rental_info_divider',
                HtmlField::class,
                HtmlFieldOption::make()
                    ->content('<h4>' . trans('plugins/car-rentals::car-rentals.car.forms.rental_information') . '</h4>')
                    ->colspan(2)
            )
            ->add(
                'rental_type',
                SelectField::class,
                SelectFieldOption::make()
                    ->choices(CarRentalTypeEnum::labels())
                    ->label(trans('plugins/car-rentals::car-rentals.car.forms.rental_type'))
                    ->required()
            )
            ->add(
                'rental_rate',
                TextField::class,
                NumberFieldOption::make()
                    ->label(trans('plugins/car-rentals::car-rentals.car.forms.rental_rate'))
                    ->attributes([
                        'class' => 'input-mask-number form-control',
                        'step' => 'any',
                        'data-thousands-separator' => get_car_rentals_setting('thousands_separator', ','),
                        'data-decimal-separator' => get_car_rentals_setting('decimal_separator', '.'),
                        'placeholder' => trans('plugins/car-rentals::car-rentals.car.placeholders.rental_rate'),
                    ])
                    ->required()
            )
            ->add(
                'tax_id',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/car-rentals::car-rentals.car.forms.tax'))
                    ->choices($taxes)
                    ->emptyValue(trans('plugins/car-rentals::car-rentals.car.forms.select_tax'))
            )
            ->add(
                'pick_address_id',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/car-rentals::car-rentals.car.forms.pick_address'))
                    ->colspan(2)
                    ->choices($addressOptions)
                    ->searchable()
                    ->required()
            )
            ->add(
                'is_same_drop_off',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/car-rentals::car-rentals.car.forms.same_drop_off'))
                    ->value($isSameDropOff)
            )
            ->add(
                'return_address_id',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/car-rentals::car-rentals.car.forms.return_address'))
                    ->colspan(2)
                    ->choices($addressOptions)
                    ->searchable()
            )
            ->add(
                'external_booking_url',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/car-rentals::car-rentals.car.forms.external_booking_url'))
                    ->helperText(trans('plugins/car-rentals::car-rentals.car.forms.external_booking_url_helper'))
                    ->placeholder(trans('plugins/car-rentals::car-rentals.car.placeholders.external_booking_url'))
            )
            ->addCloseCollapsible('car_purpose', 'rent')

            // Selling Information (shown when car_purpose is 'sale')
            ->addOpenCollapsible('car_purpose', 'sale', $carPurpose, attributes: ['class' => 'form-fieldset col-lg-12'])
            ->add(
                'selling_info_divider',
                HtmlField::class,
                HtmlFieldOption::make()
                    ->content('<h4>' . trans('plugins/car-rentals::car-rentals.car.forms.selling_information') . '</h4>')
                    ->colspan(2)
            )
            ->add(
                'sale_price',
                TextField::class,
                NumberFieldOption::make()
                    ->label(trans('plugins/car-rentals::car-rentals.car.forms.sale_price'))
                    ->attributes([
                        'class' => 'input-mask-number form-control',
                        'step' => 'any',
                        'data-thousands-separator' => get_car_rentals_setting('thousands_separator', ','),
                        'data-decimal-separator' => get_car_rentals_setting('decimal_separator', '.'),
                        'placeholder' => trans('plugins/car-rentals::car-rentals.car.placeholders.sale_price'),
                    ])
                    ->required()
            )
            ->add(
                'condition',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/car-rentals::car-rentals.car.forms.condition'))
                    ->choices(CarConditionEnum::labels())
                    ->emptyValue(trans('plugins/car-rentals::car-rentals.car.forms.select_condition'))
                    ->required()
            )
            ->add(
                'ownership_history',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/car-rentals::car-rentals.car.forms.ownership_history'))
                    ->attributes([
                        'placeholder' => trans('plugins/car-rentals::car-rentals.car.placeholders.ownership_history'),
                    ])
            )
            ->add(
                'warranty_information',
                TextareaField::class,
                TextareaFieldOption::make()
                    ->label(trans('plugins/car-rentals::car-rentals.car.forms.warranty_information'))
                    ->attributes([
                        'rows' => 3,
                        'placeholder' => trans('plugins/car-rentals::car-rentals.car.placeholders.warranty_information'),
                    ])
            )
            ->add(
                'sale_status',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/car-rentals::car-rentals.car.forms.sale_status'))
                    ->choices(CarForSaleStatusEnum::labels())
                    ->required()
            )
            ->addCloseCollapsible('car_purpose', 'sale')
            ->add(
                'make_id',
                SelectField::class,
                SelectFieldOption::make()
                    ->choices(CarMake::query()->wherePublished()->pluck('name', 'id')->all())
                    ->label(trans('plugins/car-rentals::car-rentals.car.forms.make'))
                    ->when(is_in_admin(true), function (FormFieldOptions $option): void {
                        $option->helperText(trans('plugins/car-rentals::car-rentals.car.forms.add_new_attribute', [
                            'url' => route('car-rentals.car-makes.create'),
                        ]));
                    })
                    ->emptyValue(trans('plugins/car-rentals::car-rentals.car.forms.none'))
            )
            ->add(
                'vehicle_type_id',
                SelectField::class,
                SelectFieldOption::make()
                    ->choices(CarType::query()->wherePublished()->pluck('name', 'id')->all())
                    ->label(trans('plugins/car-rentals::car-rentals.car.forms.vehicle_type'))
                    ->when(is_in_admin(true), function (FormFieldOptions $option): void {
                        $option->helperText(trans('plugins/car-rentals::car-rentals.car.forms.add_new_attribute', [
                            'url' => route('car-rentals.car-types.create'),
                        ]));
                    })
                    ->emptyValue(trans('plugins/car-rentals::car-rentals.car.forms.none'))
            )
            ->add(
                'transmission_id',
                SelectField::class,
                SelectFieldOption::make()
                    ->choices(CarTransmission::query()->wherePublished()->pluck('name', 'id')->all())
                    ->label(trans('plugins/car-rentals::car-rentals.car.forms.transmission'))
                    ->when(is_in_admin(true), function (FormFieldOptions $option): void {
                        $option->helperText(trans('plugins/car-rentals::car-rentals.car.forms.add_new_attribute', [
                            'url' => route('car-rentals.car-transmissions.create'),
                        ]));
                    })
                    ->emptyValue(trans('plugins/car-rentals::car-rentals.car.forms.none'))
            )
            ->add(
                'fuel_type_id',
                SelectField::class,
                SelectFieldOption::make()
                    ->choices(CarFuel::query()->wherePublished()->pluck('name', 'id')->all())
                    ->label(trans('plugins/car-rentals::car-rentals.car.forms.fuel_type'))
                    ->when(is_in_admin(true), function (FormFieldOptions $option): void {
                        $option->helperText(trans('plugins/car-rentals::car-rentals.car.forms.add_new_attribute', [
                            'url' => route('car-rentals.car-fuels.create'),
                        ]));
                    })
                    ->emptyValue(trans('plugins/car-rentals::car-rentals.car.forms.none'))
            )
            ->add(
                'license_plate',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/car-rentals::car-rentals.car.forms.license_plate'))
                    ->maxLength(20)
                    ->placeholder(trans('plugins/car-rentals::car-rentals.car.placeholders.license_plate'))
            )
            ->add(
                'vin',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/car-rentals::car-rentals.car.forms.vin'))
                    ->placeholder(trans('plugins/car-rentals::car-rentals.car.placeholders.vin'))
            )
            ->add(
                'year',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/car-rentals::car-rentals.car.forms.year'))
                    ->maxLength(5)
                    ->placeholder(trans('plugins/car-rentals::car-rentals.car.placeholders.year'))
            )
            ->add(
                'mileage',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/car-rentals::car-rentals.car.forms.mileage'))
                    ->maxLength(5)
                    ->placeholder(trans('plugins/car-rentals::car-rentals.car.placeholders.mileage'))
            )
            ->add(
                'number_of_seats',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('plugins/car-rentals::car-rentals.car.forms.number_of_seats'))
                    ->min(0)
                    ->placeholder(trans('plugins/car-rentals::car-rentals.car.placeholders.number_of_seats'))
            )
            ->add(
                'number_of_doors',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('plugins/car-rentals::car-rentals.car.forms.number_of_doors'))
                    ->min(0)
                    ->placeholder(trans('plugins/car-rentals::car-rentals.car.placeholders.number_of_doors'))
            )
            ->add(
                'insurance_info',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/car-rentals::car-rentals.car.forms.insurance_info'))
                    ->colspan(2)
                    ->placeholder(trans('plugins/car-rentals::car-rentals.car.placeholders.insurance_info'))
            )
            ->add('images[]', MediaImagesField::class, [
                'label' => trans('plugins/car-rentals::car-rentals.car.forms.images'),
                'values' => $this->getModel()->getKey() ? $this->getModel()->images : [],
                'colspan' => 2,
            ])
            ->addMetaBoxes([
                'service_histories' => [
                    'title' => trans('plugins/car-rentals::car-rentals.car.maintenance_history.name'),
                    'content' => view('plugins/car-rentals::cars.maintenance-histories.index', [
                        'serviceHistories' => $this->getModel()->serviceHistories,
                    ])->render(),
                    'attributes' => [
                        'id' => 'maintenance-histories-table',
                    ],
                    'header_actions' => view('plugins/car-rentals::cars.maintenance-histories.header-actions-button', [
                        'modalTarget' => '#add-service-history-modal',
                        'label' => trans('plugins/car-rentals::car-rentals.car.maintenance_history.forms.add_maintenance_history'),
                    ])->render(),
                    'has_table' => true,
                ],
            ])
            ->add('status', SelectField::class, StatusFieldOption::make()->choices(CarStatusEnum::labels())->required())
            ->when($this->getModel()->exists, function (FormAbstract $form): void {
                $form->add(
                    'moderation_status',
                    HtmlField::class,
                    HtmlFieldOption::make()
                        ->label(trans('plugins/car-rentals::car-rentals.car.forms.moderation_status'))
                        ->content(view('plugins/car-rentals::cars.partials.moderation-status', [
                            'model' => $this->getModel(),
                        ])->render())
                );
            })
            ->when(CarRentalsHelper::isMultiVendorEnabled(), function (FormAbstract $form): void {
                $form
                    ->add(
                        'author_id',
                        AutocompleteField::class,
                        AutocompleteFieldOption::make()
                        ->label(trans('plugins/car-rentals::car-rentals.car.forms.customer'))
                        ->ajaxUrl(route('car-rentals.customers.list'))
                        ->when($this->getModel()->author_id, function (AutocompleteFieldOption $option): void {
                            $option->choices([$this->model->author->id => $this->model->author->name]);
                        })
                        ->emptyValue(trans('plugins/car-rentals::car-rentals.car.forms.select_customer'))
                        ->allowClear()
                    );
            })
            ->add(
                'is_featured',
                OnOffField::class,
                IsFeaturedFieldOption::make()
            )
            ->add(
                'is_used',
                OnOffField::class,
                CheckboxFieldOption::make()
                    ->label(trans('plugins/car-rentals::car-rentals.car.forms.is_used'))
                    ->defaultValue(0)
            )
            ->add(
                'tags',
                TagField::class,
                TagFieldOption::make()
                    ->label(trans('plugins/car-rentals::car-rentals.car.forms.tags'))
                    ->choices($carTags)
                    ->selected(implode(',', $selectedTags))
                    ->placeholder(trans('plugins/car-rentals::car-rentals.car.placeholders.tags'))
                    ->when(is_in_admin(true), function (FormFieldOptions $option): void {
                        $option->helperText(trans('plugins/car-rentals::car-rentals.car.forms.add_new_attribute', [
                            'url' => route('car-rentals.car-tags.create'),
                        ]));
                    })
            )
            ->add(
                'categories[]',
                TreeCategoryField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/car-rentals::car-rentals.car.forms.categories'))
                    ->choices($categoriesWithChildren)
                    ->when($this->getModel()->getKey(), function (SelectFieldOption $fieldOption) {
                        /**
                         * @var Car $car
                         */
                        $car = $this->getModel();

                        return $fieldOption->selected($car->categories()->pluck('cr_car_category_id')->all());
                    }, function (SelectFieldOption $fieldOption) {
                        return $fieldOption
                            ->selected(
                                CarCategory::query()
                                    ->where('is_default', 1)
                                    ->pluck('id')
                                    ->all()
                            );
                    })
            )
            ->add(
                'amenities',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/car-rentals::car-rentals.car.forms.amenities'))
                    ->searchable()
                    ->multiple()
                    ->choices($carAmenities)
                    ->selected($selectedAmenities)
                    ->emptyValue(trans('plugins/car-rentals::car-rentals.car.placeholders.amenities'))
            )
            ->add(
                'colors',
                TagField::class,
                TagFieldOption::make()
                    ->label(trans('plugins/car-rentals::car-rentals.car.forms.colors'))
                    ->choices($carColors)
                    ->selected(implode(',', $selectedColors))
                    ->placeholder(trans('plugins/car-rentals::car-rentals.car.placeholders.colors'))
            )
            ->setBreakFieldPoint('status');
    }
}
