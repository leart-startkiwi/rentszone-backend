<?php

namespace Botble\CarRentals\Providers;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\RenderingAdminWidgetEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\CarRentals\Events\BookingCreated;
use Botble\CarRentals\Events\BookingProcessingEvent;
use Botble\CarRentals\Events\BookingStatusChanged;
use Botble\CarRentals\Events\CarViewed;
use Botble\CarRentals\Listeners\BookingCompletedListener;
use Botble\CarRentals\Listeners\GenerateInvoiceListener;
use Botble\CarRentals\Listeners\RegisterBookingReportsWidget;
use Botble\CarRentals\Listeners\SaveCarFaqListener;
use Botble\CarRentals\Listeners\SendBookingConfirmationEmailListener;
use Botble\CarRentals\Listeners\SendStatusChangedNotificationListener;
use Botble\CarRentals\Listeners\UpdateCarViewListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        RenderingAdminWidgetEvent::class => [
            RegisterBookingReportsWidget::class,
        ],
        BookingCreated::class => [
            GenerateInvoiceListener::class,
        ],
        CarViewed::class => [
            UpdateCarViewListener::class,
        ],
        CreatedContentEvent::class => [
            SaveCarFaqListener::class,
        ],
        UpdatedContentEvent::class => [
            SaveCarFaqListener::class,
        ],
        BookingProcessingEvent::class => [
            SendBookingConfirmationEmailListener::class,
        ],
        BookingStatusChanged::class => [
            SendStatusChangedNotificationListener::class,
            BookingCompletedListener::class,
        ],
    ];
}
