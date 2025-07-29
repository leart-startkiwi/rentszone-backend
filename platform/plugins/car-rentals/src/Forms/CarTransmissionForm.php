<?php

namespace Botble\CarRentals\Forms;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\CarRentals\Http\Requests\CarTypeRequest;
use Botble\CarRentals\Models\CarTransmission;

class CarTransmissionForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(CarTransmission::class)
            ->setValidatorClass(CarTypeRequest::class)
            ->add(
                'name',
                TextField::class,
                NameFieldOption::make()
                    ->required()
                    ->maxLength(120)
            )
            ->add('status', SelectField::class, StatusFieldOption::make()->choices(BaseStatusEnum::labels()))
            ->add('icon', MediaImageField::class)
            ->setBreakFieldPoint('status');
    }
}
