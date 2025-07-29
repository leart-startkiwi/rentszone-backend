<?php

namespace Botble\CarRentals\Listeners;

use Botble\CarRentals\Events\BookingCreated;
use Botble\CarRentals\Supports\InvoiceHelper;

class GenerateInvoiceListener
{
    public function handle(BookingCreated $event): void
    {
        (new InvoiceHelper())->store($event->booking);
    }
}
