<?php

namespace Botble\CarRentals\Forms\Vendor;

use Botble\Base\Forms\FieldOptions\ContentFieldOption;
use Botble\Base\Forms\FormFieldOptions;
use Botble\CarRentals\Facades\CarRentalsHelper;
use Botble\CarRentals\Forms\CarForm as BaseCarForm;
use Botble\CarRentals\Forms\Fields\CustomEditorField;
use Botble\CarRentals\Forms\Fields\MultipleUploadField;
use Botble\CarRentals\Http\Requests\CarRequest;

class CarForm extends BaseCarForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->template('plugins/car-rentals::themes.vendor-dashboard.cars.form')
            ->hasFiles()
            ->setValidatorClass(CarRequest::class)
            ->remove('is_featured')
            ->remove('moderation_status')
            ->remove('content')
            ->remove('images[]')
            ->remove('author_id')
            ->addAfter(
                'description',
                'content',
                CustomEditorField::class,
                ContentFieldOption::make()
                    ->required()
                    ->colspan(2)
            )
            ->addAfter(
                'content',
                'images',
                MultipleUploadField::class,
                FormFieldOptions::make()
                    ->label(trans('plugins/car-rentals::car-rentals.car.forms.vendor_images', [
                        'max' => CarRentalsHelper::maxPostImagesUploadByVendor(),
                    ]))
                    ->colspan(2)
            );
    }
}
