<?php

namespace Botble\CarRentals\Forms;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\CarRentals\Http\Requests\CarTagRequest;
use Botble\CarRentals\Models\CarTag;

class CarTagForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(CarTag::class)
            ->setValidatorClass(CarTagRequest::class)
            ->withCustomFields()
            ->add(
                'name',
                TextField::class,
                NameFieldOption::make()
                    ->required()
                    ->maxLength(120)
            )
            ->add(
                'description',
                TextareaField::class,
                DescriptionFieldOption::make()
                    ->maxLength(400)
            )
            ->add('status', SelectField::class, StatusFieldOption::make()->choices(BaseStatusEnum::labels()))
            ->setBreakFieldPoint('status');
    }
}
