<?php

namespace Botble\CarRentals\Listeners;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\EmailHandler;
use Botble\CarRentals\Events\BookingStatusChanged;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendStatusChangedNotificationListener implements ShouldQueue
{
    public function handle(BookingStatusChanged $event): void
    {
        $booking = $event->booking;

        EmailHandler::setModule(CAR_RENTALS_MODULE_SCREEN_NAME)
            ->setVariableValues([
                'booking_name' => $booking->customer_name,
                'booking_date' => BaseHelper::formatDateTime($booking->created_at),
                'booking_status' => $booking->status->label(),
                'booking_link' => route('customer.bookings.show', $booking->transaction_id),
            ])
            ->sendUsingTemplate('booking-status-changed', $booking->customer_email);
    }
}
