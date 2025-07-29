<?php

namespace Botble\CarRentals\Events;

use Botble\CarRentals\Models\Booking;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingProcessingEvent
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public Booking $booking)
    {
    }
}
