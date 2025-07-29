<?php

namespace Botble\CarRentals\Widgets;

use Botble\Base\Widgets\Html;
use Botble\CarRentals\Enums\BookingStatusEnum;
use Botble\CarRentals\Models\Booking;
use Botble\CarRentals\Models\Car;
use Botble\CarRentals\Models\Customer;
use Botble\Payment\Enums\PaymentStatusEnum;
use Illuminate\Support\Facades\DB;

class ReportSummaryHtml extends Html
{
    public function getContent(): string
    {
        $bookingsCount = Booking::query()
            ->whereDate('created_at', '>=', $this->startDate)
            ->whereDate('created_at', '<=', $this->endDate)
            ->count();

        $completedBookings = Booking::query()
            ->where('status', BookingStatusEnum::COMPLETED)
            ->whereDate('created_at', '>=', $this->startDate)
            ->whereDate('created_at', '<=', $this->endDate)
            ->count();

        $pendingBookings = Booking::query()
            ->where('status', BookingStatusEnum::PENDING)
            ->whereDate('created_at', '>=', $this->startDate)
            ->whereDate('created_at', '<=', $this->endDate)
            ->count();

        $processingBookings = Booking::query()
            ->where('status', BookingStatusEnum::PROCESSING)
            ->whereDate('created_at', '>=', $this->startDate)
            ->whereDate('created_at', '<=', $this->endDate)
            ->count();

        $cancelledBookings = Booking::query()
            ->where('status', BookingStatusEnum::CANCELLED)
            ->whereDate('created_at', '>=', $this->startDate)
            ->whereDate('created_at', '<=', $this->endDate)
            ->count();

        $revenue = [];

        if (is_plugin_active('payment')) {
            $revenues = Booking::query()
                ->select([
                    DB::raw('SUM(COALESCE(payments.amount, 0) - COALESCE(payments.refunded_amount, 0)) as revenue'),
                    'payments.status',
                ])
                ->join('payments', 'payments.id', '=', 'cr_bookings.payment_id')
                ->whereIn('payments.status', [PaymentStatusEnum::COMPLETED, PaymentStatusEnum::PENDING])
                ->whereDate('payments.created_at', '>=', $this->startDate)
                ->whereDate('payments.created_at', '<=', $this->endDate)
                ->groupBy('payments.status')
                ->get();

            $revenueCompleted = $revenues->firstWhere('status', PaymentStatusEnum::COMPLETED);
            $revenuePending = $revenues->firstWhere('status', PaymentStatusEnum::PENDING);

            $revenue = [
                'revenue' => ($revenueCompleted ? (int) $revenueCompleted->revenue : 0) + ($revenuePending ? (int) $revenuePending->revenue : 0),
            ];
        }

        // Get top performing cars
        $topCars = Car::query()
            ->select('cr_cars.*')
            ->selectRaw('(
                SELECT COUNT(*)
                FROM cr_bookings
                INNER JOIN cr_booking_cars ON cr_booking_cars.booking_id = cr_bookings.id
                WHERE cr_cars.id = cr_booking_cars.car_id
                AND cr_bookings.created_at >= ?
                AND cr_bookings.created_at <= ?
            ) as bookings_count', [$this->startDate->toDateTimeString(), $this->endDate->toDateTimeString()])
            ->selectRaw('(
                SELECT COALESCE(SUM(cr_bookings.amount), 0)
                FROM cr_bookings
                INNER JOIN cr_booking_cars ON cr_booking_cars.booking_id = cr_bookings.id
                WHERE cr_cars.id = cr_booking_cars.car_id
                AND cr_bookings.created_at >= ?
                AND cr_bookings.created_at <= ?
            ) as revenue', [$this->startDate->toDateTimeString(), $this->endDate->toDateTimeString()])
            ->with('type')
            ->having('bookings_count', '>', 0)
            ->orderByDesc('bookings_count')
            ->limit(5)
            ->get();

        // Get recent customers
        $recentCustomers = Customer::query()
            ->select('cr_customers.*')
            ->selectRaw('(
                SELECT COUNT(*)
                FROM cr_bookings
                WHERE cr_bookings.customer_id = cr_customers.id
                AND cr_bookings.created_at >= ?
                AND cr_bookings.created_at <= ?
            ) as bookings_count', [$this->startDate->toDateTimeString(), $this->endDate->toDateTimeString()])
            ->selectRaw('(
                SELECT COALESCE(SUM(cr_bookings.amount), 0)
                FROM cr_bookings
                WHERE cr_bookings.customer_id = cr_customers.id
                AND cr_bookings.created_at >= ?
                AND cr_bookings.created_at <= ?
            ) as total_spent', [$this->startDate->toDateTimeString(), $this->endDate->toDateTimeString()])
            ->having('bookings_count', '>', 0)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $startDate = $this->startDate;
        $endDate = $this->endDate;

        return view('plugins/car-rentals::reports.widgets.summary-section', compact(
            'bookingsCount',
            'completedBookings',
            'pendingBookings',
            'processingBookings',
            'cancelledBookings',
            'revenue',
            'startDate',
            'endDate'
        ))->render() .
        '<div class="row row-cards mb-4">' .
        '<div class="col-lg-8">' .
        view('plugins/car-rentals::reports.widgets.booking-status', compact(
            'bookingsCount',
            'completedBookings',
            'pendingBookings',
            'processingBookings',
            'cancelledBookings'
        ))->render() .
        '</div>' .
        '<div class="col-lg-4">' .
        view('plugins/car-rentals::reports.widgets.recent-customers', compact('recentCustomers'))->render() .
        '</div>' .
        '</div>' .
        '<div class="mb-4">' .
        view('plugins/car-rentals::reports.widgets.top-cars', compact('topCars'))->render() .
        '</div>';
    }
}
