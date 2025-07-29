<?php

namespace Botble\CarRentals\Forms\Vendor;

use Botble\CarRentals\Forms\BookingForm as BaseBookingForm;

class BookingForm extends BaseBookingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->template('plugins/car-rentals::themes.vendor-dashboard.cars.form')
            ->removeMetaBox('information')
            ->addMetaBoxes([
                'information' => [
                    'title' => trans('plugins/car-rentals::booking.booking_information'),
                    'content' => view('plugins/car-rentals::bookings.information', [
                        'booking' => $this->getModel(),
                        'route' => 'car-rentals.vendor.invoices.generate',
                    ])->render(),
                    'attributes' => [
                        'style' => 'margin-top: 0',
                    ],
                ],
            ]);
    }
}
