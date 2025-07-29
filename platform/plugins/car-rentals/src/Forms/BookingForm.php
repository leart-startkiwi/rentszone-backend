<?php

namespace Botble\CarRentals\Forms;

use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\FormAbstract;
use Botble\CarRentals\Enums\BookingStatusEnum;
use Botble\CarRentals\Http\Requests\UpdateBookingRequest;
use Botble\CarRentals\Models\Booking;

class BookingForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(Booking::class)
            ->setValidatorClass(UpdateBookingRequest::class)
            ->withCustomFields()
            ->add('status', SelectField::class, StatusFieldOption::make()->choices(BookingStatusEnum::labels()))
            ->setBreakFieldPoint('status')
            ->addMetaBoxes([
                'information' => [
                    'title' => trans('plugins/car-rentals::booking.booking_information'),
                    'content' => view('plugins/car-rentals::bookings.information', ['booking' => $this->getModel()])->render(),
                    'attributes' => [
                        'style' => 'margin-top: 0',
                    ],
                ],
            ]);
    }
}
