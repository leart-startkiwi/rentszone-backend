<?php

namespace Botble\CarRentals\Listeners;

use Botble\Base\Facades\EmailHandler;
use Botble\CarRentals\Events\BookingProcessingEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendBookingConfirmationEmailListener implements ShouldQueue
{
    public function handle(BookingProcessingEvent $event): void
    {
        $mailer = EmailHandler::setModule(CAR_RENTALS_MODULE_SCREEN_NAME);

        $booking = $event->booking;

        $mailer->setVariableValues([
            'booking_code' => $booking->booking_number,
            'customer_name' => $booking->customer_name,
            'customer_email' => $booking->customer_email,
            'customer_phone' => $booking->customer_phone,
            'car_name' => $booking->car->car_name,
            'payment_method' => $booking->payment ? $booking->payment->payment_channel->label() : 'N/A',
            'pickup_address' => $booking->car->pickup_address_text,
            'return_address' => $booking->car->return_address_text,
            'rental_start_date' => $booking->car->rental_start_date,
            'rental_end_date' => $booking->car->rental_end_date,
            'note' => $booking->note,
        ]);

        $mailer->sendUsingTemplate('booking-confirm', $booking->customer_email);
        $mailer->sendUsingTemplate('booking-notice-to-admin');
    }
}
