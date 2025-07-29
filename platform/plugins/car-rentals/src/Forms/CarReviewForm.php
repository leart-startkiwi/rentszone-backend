<?php

namespace Botble\CarRentals\Forms;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\FormAbstract;
use Botble\CarRentals\Http\Requests\CarReviewRequest;
use Botble\CarRentals\Models\CarReview;

class CarReviewForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(CarReview::class)
            ->setValidatorClass(CarReviewRequest::class)
            ->withCustomFields()
            ->add('status', SelectField::class, StatusFieldOption::make()->choices(BaseStatusEnum::labels()))
            ->setBreakFieldPoint('status')
            ->addMetaBoxes([
                'information' => [
                    'title' => trans('plugins/car-rentals::car-rentals.review.name'),
                    'content' => view('plugins/car-rentals::reviews.index', ['review' => $this->getModel()])->render(),
                    'attributes' => [
                        'style' => 'margin-top: 0',
                    ],
                ],
            ]);
    }
}
