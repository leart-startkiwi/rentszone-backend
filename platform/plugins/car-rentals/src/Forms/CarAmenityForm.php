<?php

namespace Botble\CarRentals\Forms;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\CarRentals\Http\Requests\CarAmenityRequest;
use Botble\CarRentals\Models\CarAmenity;

class CarAmenityForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(CarAmenity::class)
            ->setValidatorClass(CarAmenityRequest::class)
            ->withCustomFields()
            ->columns()
            ->add(
                'name',
                TextField::class,
                NameFieldOption::make()
                    ->required()
                    ->maxLength(120)
                    ->colspan(2)
            )
            ->add('status', SelectField::class, StatusFieldOption::make()->choices(BaseStatusEnum::labels()))
            ->setBreakFieldPoint('status');
    }
}
