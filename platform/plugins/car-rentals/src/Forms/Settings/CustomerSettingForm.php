<?php

namespace Botble\CarRentals\Forms\Settings;

use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\CarRentals\Facades\CarRentalsHelper;
use Botble\CarRentals\Http\Requests\Settings\CustomerSettingRequest;
use Botble\Setting\Forms\SettingForm;

class CustomerSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/car-rentals::settings.customer.title'))
            ->setSectionDescription(trans('plugins/car-rentals::settings.customer.description'))
            ->setFormOptions([
                'class' => 'main-setting-form',
            ])
            ->setValidatorClass(CustomerSettingRequest::class)
            ->add(
                'enabled_customer_registration',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->value(CarRentalsHelper::isEnabledCustomerRegistration())
                    ->label(trans('plugins/car-rentals::settings.customer.forms.enabled_customer_registration'))
                    ->helperText(trans('plugins/car-rentals::settings.customer.forms.enabled_customer_registration_helper'))
            )
            ->add(
                'verify_customer_email',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/car-rentals::settings.customer.forms.verify_customer_email'))
                    ->helperText(trans('plugins/car-rentals::settings.customer.forms.verify_customer_email_helper'))
                    ->value(CarRentalsHelper::isEnabledEmailVerification())
            )
            ->add(
                'show_terms_and_policy_acceptance_checkbox',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->defaultValue((bool) get_car_rentals_setting('show_terms_and_policy_acceptance_checkbox', true))
                    ->label(trans('plugins/car-rentals::settings.customer.forms.show_terms_checkbox'))
                    ->helperText(trans('plugins/car-rentals::settings.customer.forms.show_terms_checkbox_helper'))
            )
            ->add(
                'max_filesize_upload_by_vendor',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('plugins/car-rentals::settings.customer.forms.max_upload_filesize'))
                    ->value(CarRentalsHelper::maxFilesizeUploadByVendor())
                    ->placeholder(trans('plugins/car-rentals::settings.customer.forms.max_upload_filesize_placeholder', [
                        'size' => CarRentalsHelper::maxFilesizeUploadByVendor(),
                    ]))
                    ->helperText(trans('plugins/car-rentals::settings.customer.forms.max_upload_filesize_helper'))
            )
            ->add(
                'max_post_images_upload_by_vendor',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('plugins/car-rentals::settings.customer.forms.max_post_images_upload_by_vendor'))
                    ->value(CarRentalsHelper::maxPostImagesUploadByVendor())
                    ->helperText(trans('plugins/car-rentals::settings.customer.forms.max_post_images_upload_by_vendor_helper'))
            )
            ->add(
                'car_rentals_customer_default_avatar',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(trans('plugins/car-rentals::settings.customer.forms.default_avatar'))
                    ->helperText(trans('plugins/car-rentals::settings.customer.forms.default_avatar_helper'))
                    ->value(setting('car_rentals_customer_default_avatar'))
            );
    }
}
