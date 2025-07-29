<?php

namespace Botble\CarRentals\Forms\Settings;

use Botble\Base\Forms\FieldOptions\MultiChecklistFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\Fields\MultiCheckListField;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\CarRentals\Facades\CarRentalsHelper;
use Botble\CarRentals\Http\Requests\Settings\TaxSettingRequest;
use Botble\CarRentals\Models\Tax;
use Botble\Setting\Forms\SettingForm;

class TaxSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $taxes = Tax::query()
            ->selectRaw('CONCAT(name, " (", FORMAT(percentage, 2), "%)") as name, id')
            ->wherePublished()
            ->pluck('name', 'id')
            ->all();

        $taxApplied = CarRentalsHelper::getAppliedTaxes();

        $taxIdsSelected = $taxApplied ? $taxApplied->pluck('id')->all() : [];

        $this
            ->setValidatorClass(TaxSettingRequest::class)
            ->setSectionTitle(trans('plugins/car-rentals::settings.tax.tax_setting'))
            ->setSectionDescription(trans('plugins/car-rentals::settings.tax.tax_setting_description'))
            ->contentOnly()
            ->add(
                'tax_enabled',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->value(CarRentalsHelper::isTaxEnabled())
                    ->label(trans('plugins/car-rentals::settings.tax.forms.enable_tax'))
            )
            ->add(
                'tax_active_ids[]',
                MultiCheckListField::class,
                MultiChecklistFieldOption::make()
                    ->selected($taxIdsSelected)
                    ->choices($taxes)
                    ->label(trans('plugins/car-rentals::settings.tax.forms.apply_tax'))
                    ->collapsible(
                        'tax_enabled',
                        1,
                        $this->getField('tax_enabled')->getValue()
                    )
            )
        ;
    }
}
