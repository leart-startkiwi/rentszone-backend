<?php

namespace Botble\CarRentals\Listeners;

use Botble\Base\Events\RenderingAdminWidgetEvent;
use Botble\CarRentals\Widgets\BookingCard;
use Botble\CarRentals\Widgets\BookingChart;
use Botble\CarRentals\Widgets\CarCard;
use Botble\CarRentals\Widgets\CustomerCard;
use Botble\CarRentals\Widgets\CustomerChart;
use Botble\CarRentals\Widgets\RecentBookingsTable;
use Botble\CarRentals\Widgets\ReportGeneralHtml;
use Botble\CarRentals\Widgets\ReportSummaryHtml;
use Botble\CarRentals\Widgets\RevenueCard;

class RegisterBookingReportsWidget
{
    public function handle(RenderingAdminWidgetEvent $event): void
    {
        $event->widget->register([
            ReportSummaryHtml::class,
            RevenueCard::class,
            CarCard::class,
            CustomerCard::class,
            BookingCard::class,
            CustomerChart::class,
            BookingChart::class,
            ReportGeneralHtml::class,
            RecentBookingsTable::class,
        ], 'booking-reports');
    }
}
