<?php

namespace Botble\CarRentals\Forms\Settings;

use Botble\Base\Forms\FieldOptions\RadioFieldOption;
use Botble\Base\Forms\Fields\GoogleFontsField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\RadioField;
use Botble\CarRentals\Http\Requests\Settings\InvoiceSettingRequest;
use Botble\CarRentals\Supports\InvoiceHelper;
use Botble\Setting\Forms\SettingForm;

class InvoiceSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/car-rentals::settings.invoice.title'))
            ->setSectionDescription(trans('plugins/car-rentals::settings.invoice.description'))
            ->setValidatorClass(InvoiceSettingRequest::class)
            ->addCustomField('googleFonts', GoogleFontsField::class)
            ->add('company_name_for_invoicing', 'text', [
                'label' => trans('plugins/car-rentals::settings.invoice.forms.company_name'),
                'value' => get_car_rentals_setting('company_name_for_invoicing', theme_option('site_title')),
            ])
            ->add('company_address_for_invoicing', 'text', [
                'label' => trans('plugins/car-rentals::settings.invoice.forms.company_address'),
                'value' => get_car_rentals_setting('company_address_for_invoicing'),
            ])
            ->add('company_email_for_invoicing', 'text', [
                'label' => trans('plugins/car-rentals::settings.invoice.forms.company_email'),
                'value' => get_car_rentals_setting('company_email_for_invoicing', get_admin_email()->first()),
            ])
            ->add('company_phone_for_invoicing', 'text', [
                'label' => trans('plugins/car-rentals::settings.invoice.forms.company_phone'),
                'value' => get_car_rentals_setting('company_phone_for_invoicing'),
            ])
            ->add('company_logo_for_invoicing', MediaImageField::class, [
                'label' => trans('plugins/car-rentals::settings.invoice.forms.company_logo'),
                'value' => get_car_rentals_setting('company_logo_for_invoicing') ?: theme_option('logo'),
                'allow_thumb' => false,
            ])
            ->add('using_custom_font_for_invoice', 'onOffCheckbox', [
                'label' => trans('plugins/car-rentals::settings.invoice.forms.using_custom_font_for_invoice'),
                'value' => get_car_rentals_setting('using_custom_font_for_invoice', false),
                'attr' => [
                    'data-bb-toggle' => 'collapse',
                    'data-bb-target' => '.custom-font-settings',
                ],
            ])
            ->add('open_fieldset_custom_font_settings', 'html', [
                'html' => sprintf(
                    '<fieldset class="form-fieldset custom-font-settings" style="display: %s;" data-bb-value="1">',
                    get_car_rentals_setting('using_custom_font_for_invoice', false) ? 'block' : 'none'
                ),
            ])
            ->add('invoice_font_family', 'googleFonts', [
                'label' => trans('plugins/car-rentals::settings.invoice.forms.invoice_font_family'),
                'selected' => get_car_rentals_setting('invoice_font_family'),
            ])
            ->add('close_fieldset_custom_font_settings', 'html', [
                'html' => '</fieldset>',
            ])
            ->add('invoice_support_arabic_language', 'onOffCheckbox', [
                'label' => trans('plugins/car-rentals::settings.invoice.forms.invoice_support_arabic_language'),
                'value' => get_car_rentals_setting('invoice_support_arabic_language', false),
            ])
            ->add('enable_invoice_stamp', 'onOffCheckbox', [
                'label' => trans('plugins/car-rentals::settings.invoice.forms.enable_invoice_stamp'),
                'value' => get_car_rentals_setting('enable_invoice_stamp', true),
            ])
            ->add('invoice_code_prefix', 'text', [
                'label' => trans('plugins/car-rentals::settings.invoice.forms.invoice_code_prefix'),
                'value' => get_car_rentals_setting('invoice_code_prefix', 'INV-'),
            ])
            ->add(
                'invoice_language_support',
                RadioField::class,
                RadioFieldOption::make()
                    ->label(trans('plugins/car-rentals::settings.invoice.forms.add_language_support'))
                    ->choices([
                        'default' => trans('plugins/car-rentals::settings.invoice.forms.only_latin_languages'),
                        'arabic' => 'Arabic',
                        'bangladesh' => 'Bangladesh',
                        'chinese' => 'Chinese',
                    ])
                    ->defaultValue('default')
                    ->when(
                        (new InvoiceHelper())->getLanguageSupport(),
                        function (RadioFieldOption $option, string $language): void {
                            $option->selected($language);
                        }
                    )
                    ->colspan(6)
            );
    }
}
