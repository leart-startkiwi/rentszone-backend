<?php

namespace Botble\CarRentals\Http\Controllers;

use Botble\Base\Facades\Assets;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Widgets\AdminWidget;
use Botble\CarRentals\Facades\CarRentalsHelper;
use Botble\CarRentals\Tables\Reports\RecentBookingTable;
use Illuminate\Http\Request;

class BookingReportController extends BaseController
{
    public function __construct()
    {
        $this
            ->breadcrumb()
            ->add(trans('plugins/car-rentals::car-rentals.name'));
    }

    public function index(Request $request, AdminWidget $widget)
    {
        $this->pageTitle(trans('plugins/car-rentals::booking.reports'));

        Assets::addScriptsDirectly([
            'vendor/core/plugins/car-rentals/libraries/daterangepicker/daterangepicker.js',
            'vendor/core/plugins/car-rentals/js/report.js',
        ])
            ->addStylesDirectly([
                'vendor/core/plugins/car-rentals/libraries/daterangepicker/daterangepicker.css',
                'vendor/core/plugins/car-rentals/css/report.css',
            ])
            ->addScripts(['moment']);

        Assets::usingVueJS();

        [$startDate, $endDate] = CarRentalsHelper::getDateRangeInReport($request);

        if ($request->ajax()) {
            return $this
                ->httpResponse()->setData(view('plugins/car-rentals::reports.ajax', compact('widget'))->render());
        }

        return view(
            'plugins/car-rentals::reports.index',
            compact('startDate', 'endDate', 'widget')
        );
    }

    public function getRecentBookings(RecentBookingTable $table)
    {
        return $table->renderTable();
    }
}
