<?php

namespace Botble\CarRentals\Forms\Settings;

use Botble\Base\Facades\Assets;
use Botble\Base\Forms\FieldOptions\HtmlFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\CarRentals\Enums\CommissionFeeTypeEnum;
use Botble\CarRentals\Facades\CarRentalsHelper;
use Botble\CarRentals\Http\Requests\Settings\GeneralSettingRequest;
use Botble\CarRentals\Models\CarCategory;
use Botble\Setting\Forms\SettingForm;

class GeneralSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        Assets::addStylesDirectly('vendor/core/core/base/libraries/tagify/tagify.css')
            ->addScriptsDirectly([
                'vendor/core/core/base/libraries/tagify/tagify.js',
                'vendor/core/core/base/js/tags.js',
                'vendor/core/plugins/car-rentals/js/commission-setting.js',
            ]);

        $commissionEachCategory = [];

        if (CarRentalsHelper::isCommissionCategoryFeeBasedEnabled()) {
            $commissionEachCategory = CarCategory::getCommissionEachCategory();
        }

        $this
            ->setSectionTitle(trans('plugins/car-rentals::settings.general.title'))
            ->setSectionDescription(trans('plugins/car-rentals::settings.general.description'))
            ->setFormOptions([
                'class' => 'main-setting-form',
            ])
            ->setValidatorClass(GeneralSettingRequest::class)
            ->add(
                'enabled_multi_vendor',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->value($enabledMultiVendor = get_car_rentals_setting('enabled_multi_vendor', false))
                    ->label(trans('plugins/car-rentals::settings.general.forms.enabled_multi_vendor'))
                    ->helperText(trans('plugins/car-rentals::settings.general.forms.enabled_multi_vendor_helper'))
            )
            ->addOpenCollapsible('enabled_multi_vendor', '1', $enabledMultiVendor == 1)
            ->add(
                'enable_post_approval',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/car-rentals::settings.general.forms.enabled_post_approval'))
                    ->helperText(trans('plugins/car-rentals::settings.general.forms.enabled_post_approval_helper'))
                    ->value(setting('enable_post_approval', true))
            )
            ->add(
                'commission_settings_title',
                HtmlField::class,
                HtmlFieldOption::make()
                    ->content('<h4>' . trans('plugins/car-rentals::settings.commission.title') . '</h4>')
                    ->colspan(2)
            )
            ->add(
                'commission_settings_description',
                HtmlField::class,
                HtmlFieldOption::make()
                    ->content('<p class="text-muted">' . trans('plugins/car-rentals::settings.commission.description') . '</p>')
                    ->colspan(2)
            )
            ->add(
                'rental_commission_fee',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('plugins/car-rentals::settings.commission.default_commission_fee'))
                    ->value(CarRentalsHelper::getSetting('rental_commission_fee', 0))
                    ->min(0)
                    ->max(100)
            )
            ->add(
                'commission_fee_type',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/car-rentals::settings.commission.commission_fee_type'))
                    ->choices(CommissionFeeTypeEnum::labels())
                    ->selected(CarRentalsHelper::getSetting('commission_fee_type', CommissionFeeTypeEnum::PERCENTAGE))
            )
            ->add(
                'enable_commission_fee_for_each_category',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/car-rentals::settings.commission.enable_commission_fee_for_each_category'))
                    ->value(CarRentalsHelper::isCommissionCategoryFeeBasedEnabled())
                    ->attributes([
                        'data-bb-toggle' => 'collapse',
                        'data-bb-target' => '.category-commission-fee-settings',
                    ])
            )
            ->add('category_commission_fee_fields', 'html', [
                'html' => view(
                    'plugins/car-rentals::settings.partials.category-commission-fee-fields',
                    compact('commissionEachCategory')
                )->render(),
            ])
            ->addCloseCollapsible('enabled_multi_vendor', '1')
            ->add(
                'enabled_car_rental',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->value(get_car_rentals_setting('enabled_car_rental', true))
                    ->label(trans('plugins/car-rentals::settings.general.forms.enabled_car_rental'))
                    ->helperText(trans('plugins/car-rentals::settings.general.forms.enabled_car_rental_helper'))
            )
            ->add(
                'enabled_car_sale',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->value(get_car_rentals_setting('enabled_car_sale', true))
                    ->label(trans('plugins/car-rentals::settings.general.forms.enabled_car_sale'))
                    ->helperText(trans('plugins/car-rentals::settings.general.forms.enabled_car_sale_helper'))
            )
            ->add(
                'booking_number_format_description',
                HtmlField::class,
                HtmlFieldOption::make()
                    ->label(trans('plugins/car-rentals::settings.general.forms.booking_number_format.title'))
                    ->content(
                        sprintf(
                            '<p class="text-muted small">%s</p>',
                            trans('plugins/car-rentals::settings.general.forms.booking_number_format.description', ['format' => sprintf(
                                '<span class="sample-booking-number-prefix">%s</span>%s' .
                                '<span class="sample-booking-number-suffix">%s</span>',
                                setting('booking_number_prefix') ? setting('booking_number_prefix') . '-' : '',
                                config('plugins.car-rentals.car-rentals.default_number_start_number'),
                                setting('booking_number_suffix') ? '-' . setting('booking_number_suffix') : '',
                            )])
                        )
                    )
            )
            ->addOpenFieldset('booking_number_format_section', ['class' => 'form-fieldset d-flex gap-3'])
            ->add(
                'booking_number_prefix',
                TextField::class,
                TextFieldOption::make()
                    ->wrapperAttributes(['class' => 'position-relative w-full'])
                    ->label(trans('plugins/car-rentals::settings.general.forms.booking_number_format.start_with'))
                    ->value(get_car_rentals_setting('booking_number_prefix'))
            )
            ->add(
                'booking_number_suffix',
                TextField::class,
                TextFieldOption::make()
                    ->wrapperAttributes(['class' => 'position-relative w-full'])
                    ->label(trans('plugins/car-rentals::settings.general.forms.booking_number_format.end_with'))
                    ->value(get_car_rentals_setting('booking_number_suffix'))
            )
            ->addCloseFieldset('booking_number_format_section');
    }
}
