<?php

namespace Botble\CarRentals\Forms;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FieldOptions\ContentFieldOption;
use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\EditorField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\CarRentals\Http\Requests\ServiceRequest;
use Botble\CarRentals\Models\Service;

class ServiceForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(Service::class)
            ->setValidatorClass(ServiceRequest::class)
            ->withCustomFields()
            ->add(
                'name',
                TextField::class,
                NameFieldOption::make()
                    ->required()
                    ->maxLength(120)
            )
            ->add(
                'price',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('plugins/car-rentals::car-rentals.service.forms.price'))
                    ->required()
            )
            ->add(
                'description',
                TextareaField::class,
                DescriptionFieldOption::make()->colspan(2)
            )
            ->add('content', EditorField::class, ContentFieldOption::make()->label(trans('plugins/car-rentals::car-rentals.service.forms.content'))->colspan(2))
            ->add('status', SelectField::class, StatusFieldOption::make()->label(trans('plugins/car-rentals::car-rentals.service.forms.status'))->choices(BaseStatusEnum::labels()))
            ->add('image', MediaImageField::class, MediaImageFieldOption::make()->label(trans('plugins/car-rentals::car-rentals.service.forms.image')))
            ->add('logo', MediaImageField::class, MediaImageFieldOption::make()->label(trans('plugins/car-rentals::car-rentals.service.forms.logo')))
            ->setBreakFieldPoint('status');
    }
}
