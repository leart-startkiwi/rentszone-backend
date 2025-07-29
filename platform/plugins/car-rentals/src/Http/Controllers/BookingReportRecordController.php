<?php

namespace Botble\CarRentals\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\CarRentals\Enums\BookingStatusEnum;
use Botble\CarRentals\Models\Booking;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingReportRecordController extends BaseController
{
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'start' => ['required', 'date'],
            'end' => ['required', 'date'],
        ]);

        $bookingRecordsQuery = Booking::query();

        do_action('booking_reports_before_get_records', $request);
        do_action('booking_reports_before_query', $bookingRecordsQuery);

        $startDate = $request->date('start');
        $endDate = $request->date('end');

        $bookingRecordsQuery
            ->with(['car.car', 'payment', 'invoice'])
            ->whereHas('car', function (Builder $query) use ($startDate, $endDate): void {
                $query
                    ->where(function (Builder $query) use ($startDate, $endDate): void {
                        $query
                            ->whereDate('rental_start_date', '>=', $startDate)
                            ->whereDate('rental_start_date', '<=', $endDate);
                    })
                    ->orWhere(function (Builder $query) use ($startDate, $endDate): void {
                        $query
                            ->whereDate('rental_end_date', '>=', $startDate)
                            ->whereDate('rental_end_date', '<=', $endDate);
                    });
            })
            ->whereNot('status', BookingStatusEnum::CANCELLED);

        do_action('booking_reports_after_query', $bookingRecordsQuery);

        $bookingRecords = apply_filters('booking_reports_records', $bookingRecordsQuery->get());

        do_action('booking_reports_after_get_records', $bookingRecords);

        $json = $bookingRecords->map(function (Booking $booking) {
            return [
                'id' => $booking->getKey(),
                'textColor' => match ($booking->status->getValue()) {
                    'pending' => '#715a00',
                    'completed' => '#effeff',
                    'cancelled' => '#ffe0e2',
                    default => '#e7f1ff',
                },
                'backgroundColor' => match ($booking->status->getValue()) {
                    'pending' => '#ffc300',
                    'completed' => '#36c6d3',
                    'cancelled' => '#ed6b75',
                    default => '#0d6efd',
                },
                'borderColor' => 'transparent',
                'title' => trans('plugins/car-rentals::booking.calendar_item_title', [
                    'car' => $booking->car->car_name,
                    'customer' => $booking->customer_name,
                ]),
                'detail' => apply_filters('booking_reports_detail_render', view('plugins/car-rentals::bookings.information', [
                    'booking' => $booking,
                    'displayBookingStatus' => true,
                ])->render(), $booking),
                'detailUrl' => route('car-rentals.bookings.edit', $booking),
                'start' => $booking->car->rental_start_date,
                'end' => $booking->car->rental_end_date,
            ];
        });

        return response()->json(
            apply_filters('booking_reports_records_json', $json)
        );
    }
}
