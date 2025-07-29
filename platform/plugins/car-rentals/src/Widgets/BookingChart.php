<?php

namespace Botble\CarRentals\Widgets;

use Botble\Base\Widgets\Chart;
use Botble\CarRentals\Models\Booking;
use Botble\CarRentals\Widgets\Traits\HasCategory;

class BookingChart extends Chart
{
    use HasCategory;

    protected int $columns = 6;

    public function getLabel(): string
    {
        return trans('plugins/car-rentals::booking-reports.bookings_chart');
    }

    public function getOptions(): array
    {
        $data = Booking::query()
            ->selectRaw('count(id) as total, date_format(created_at, "' . $this->dateFormat . '") as period')
            ->whereDate('created_at', '>=', $this->startDate)
            ->whereDate('created_at', '<=', $this->endDate)
            ->groupBy('period')
            ->pluck('total', 'period')
            ->all();

        return [
            'series' => [
                [
                    'name' => trans('plugins/car-rentals::booking-reports.number_of_bookings'),
                    'data' => array_values($data),
                ],
            ],
            'xaxis' => [
                'categories' => $this->translateCategories($data),
            ],
        ];
    }
}
