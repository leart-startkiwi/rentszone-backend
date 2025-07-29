<?php

namespace Botble\CarRentals\Forms;

use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\FormAbstract;
use Botble\CarRentals\Enums\MessageStatusEnum;
use Botble\CarRentals\Http\Requests\MessageRequest;
use Botble\CarRentals\Models\Message;

class MessageForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(Message::class)
            ->setValidatorClass(MessageRequest::class)
            ->add('status', SelectField::class, StatusFieldOption::make()->choices(MessageStatusEnum::labels()))
            ->addMetaBoxes([
                'information' => [
                    'title' => trans('plugins/car-rentals::message.message_information'),
                    'content' => view('plugins/car-rentals::messages.index', ['message' => $this->getModel()])->render(),
                ],
            ])
            ->setBreakFieldPoint('status');
    }
}
