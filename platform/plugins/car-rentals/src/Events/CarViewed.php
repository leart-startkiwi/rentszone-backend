<?php

namespace Botble\CarRentals\Events;

use Botble\Base\Events\Event;
use Botble\CarRentals\Models\Car;
use Carbon\CarbonInterface;
use Illuminate\Queue\SerializesModels;

class CarViewed extends Event
{
    use SerializesModels;

    public function __construct(public Car $car, public CarbonInterface $dateTime)
    {
        //
    }
}
