<?php

namespace Botble\CarRentals\Forms\Vendor;

use Botble\CarRentals\Forms\MessageForm as BaseMessageForm;

class MessageForm extends BaseMessageForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->template('plugins/car-rentals::themes.vendor-dashboard.cars.form');
    }
}
