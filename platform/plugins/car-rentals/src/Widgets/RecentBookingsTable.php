<?php

namespace Botble\CarRentals\Widgets;

use Botble\Base\Widgets\Table;
use Botble\CarRentals\Tables\Reports\RecentBookingTable as BaseRecentBookingTable;

class RecentBookingsTable extends Table
{
    protected string $table = BaseRecentBookingTable::class;

    protected string $route = 'car-rentals.booking.reports.recent-bookings';

    public function getLabel(): string
    {
        return trans('plugins/car-rentals::booking-reports.recent_bookings');
    }
}
