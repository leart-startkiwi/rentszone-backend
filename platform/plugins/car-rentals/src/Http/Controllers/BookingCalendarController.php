<?php

namespace Botble\CarRentals\Http\Controllers;

use Botble\Base\Facades\Assets;
use Botble\Base\Http\Controllers\BaseController;

class BookingCalendarController extends BaseController
{
    public function __construct()
    {
        $this
            ->breadcrumb()
            ->add(trans('plugins/car-rentals::car-rentals.name'));
    }

    public function index()
    {
        $this->pageTitle(trans('plugins/car-rentals::booking.calendar'));

        Assets::addScriptsDirectly([
            'vendor/core/plugins/car-rentals/libraries/full-calendar/index.global.min.js',
            'vendor/core/plugins/car-rentals/js/booking-reports.js',
        ]);

        Assets::usingVueJS();

        return view('plugins/car-rentals::bookings.calendar');
    }
}
