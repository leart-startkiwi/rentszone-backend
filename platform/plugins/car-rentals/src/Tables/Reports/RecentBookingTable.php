<?php

namespace Botble\CarRentals\Tables\Reports;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\Html;
use Botble\CarRentals\Models\Booking;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\StatusColumn;
use Illuminate\Database\Eloquent\Builder;

class RecentBookingTable extends TableAbstract
{
    public function setup(): void
    {
        $columns = [
            IdColumn::make(),
            FormattedColumn::make('customer_id')
                ->getValueUsing(function (FormattedColumn $column) {
                    $item = $column->getItem();

                    return $item->customer_name;
                })
                ->title(trans('plugins/car-rentals::booking.customer'))
                ->alignLeft()
                ->orderable(false)
                ->searchable(false),
            FormattedColumn::make('car')
                ->getValueUsing(function (FormattedColumn $column) {
                    $item = $column->getItem();

                    $item->load('car');

                    $car = $item->car?->car;

                    return $car ? Html::link(
                        $car->url,
                        BaseHelper::clean($car->name),
                        ['target' => '_blank']
                    ) : $item->car->car_name;
                })
                ->title(trans('plugins/car-rentals::booking.car'))
                ->alignLeft()
                ->orderable(false)
                ->searchable(false),
            FormattedColumn::make('amount')
                ->getValueUsing(function (FormattedColumn $column) {
                    $item = $column->getItem();

                    return format_price($item->amount);
                })
                ->title(trans('plugins/car-rentals::booking.amount'))
                ->alignLeft(),
            FormattedColumn::make('booking_period')
                ->getValueUsing(function (FormattedColumn $column) {
                    $item = $column->getItem();

                    $item->load('car');

                    return BaseHelper::formatDate($item->car->rental_start_date) . ' -> ' . BaseHelper::formatDate(
                        $item->car->rental_end_date
                    );
                })
                ->title(trans('plugins/car-rentals::booking.booking_period'))
                ->orderable(false)
                ->searchable(false)
                ->alignLeft(),
            CreatedAtColumn::make(),
        ];

        if (is_plugin_active('payment')) {
            $columns = array_merge($columns, [
                FormattedColumn::make('payment_id')
                    ->getValueUsing(function (FormattedColumn $column) {
                        $item = $column->getItem();

                        return BaseHelper::clean($item->payment->payment_channel->label() ?: '&mdash;');
                    })
                    ->name('payment_id')
                    ->title(trans('plugins/car-rentals::booking.payment_method'))
                    ->alignLeft()
                    ->orderable(false)
                    ->searchable(false),
                FormattedColumn::make('payment_status')
                    ->getValueUsing(function (FormattedColumn $column) {
                        $item = $column->getItem();

                        return $item->payment->status->label() ? BaseHelper::clean(
                            $item->payment->status->toHtml()
                        ) : '&mdash;';
                    })
                    ->name('payment_status')
                    ->title(trans('plugins/car-rentals::booking.payment_status_label'))
                    ->orderable(false)
                    ->searchable(false),
            ]);
        }

        $this
            ->model(Booking::class)
            ->addColumns([...$columns, StatusColumn::make()])
            ->queryUsing(function (Builder $query) {
                $query = $query
                    ->select([
                        'id',
                        'customer_id',
                        'customer_name',
                        'amount',
                        'created_at',
                        'status',
                        'payment_id',
                    ])
                    ->with(['car']);

                if (is_plugin_active('payment')) {
                    $query->with('payment');
                }

                if ($this->request->has('date_from') && $this->request->has('date_to')) {
                    $query->whereBetween('created_at', [
                        $this->request->input('date_from'),
                        $this->request->input('date_to'),
                    ]);
                }

                return $query;
            });

        $this->type = self::TABLE_TYPE_SIMPLE;
        $this->defaultSortColumn = 0;
        $this->view = $this->simpleTableView();
    }
}
