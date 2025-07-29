<?php

namespace Botble\CarRentals\Tables;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\Html;
use Botble\CarRentals\Models\Booking;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;

class BookingTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Booking::class)
            ->addBulkActions([
                DeleteBulkAction::make()->permission('car-rentals.bookings.destroy'),
            ])
            ->addHeaderAction(CreateHeaderAction::make()->route('car-rentals.bookings.create'))
            ->addActions([
                EditAction::make()->route('car-rentals.bookings.edit'),
                DeleteAction::make()->route('car-rentals.bookings.destroy'),
            ])
            ->addColumns([
                Column::make('id'),
                FormattedColumn::make('customer_name')
                    ->label(trans('plugins/car-rentals::booking.customer'))
                    ->getValueUsing(function (FormattedColumn $column) {
                        $item = $column->getItem();

                        if ($name = $item->customer_name) {
                            return $name;
                        }

                        if (! $item->customer_id) {
                            return '-';
                        }

                        return $item->customer ? $item->customer->name : '-';
                    }),
                FormattedColumn::make('amount')
                    ->label(trans('plugins/car-rentals::booking.amount'))
                    ->getValueUsing(function (FormattedColumn $column) {
                        $item = $column->getItem();

                        return Html::tag('span', format_price($item->amount, $item->currency), ['class' => 'fw-medium'])->toHtml();
                    }),
                FormattedColumn::make('rental_period')
                    ->orderable(false)
                    ->searchable(false)
                    ->label(trans('plugins/car-rentals::booking.rental_period'))
                    ->getValueUsing(function (FormattedColumn $column) {
                        $item = $column->getItem();
                        $item->loadMissing('car');

                        if (! $car = $item->car) {
                            return '-';
                        }

                        return sprintf('%s -> %s', BaseHelper::formatDate($car->rental_start_date), BaseHelper::formatDate($car->rental_end_date));
                    }),
                CreatedAtColumn::make(),
                FormattedColumn::make('payment_method')
                    ->orderable(false)
                    ->searchable(false)
                    ->getValueUsing(function (FormattedColumn $column) {
                        $item = $column->getItem();

                        if (! is_plugin_active('payment')) {
                            return '&mdash;';
                        }

                        $item->loadMissing('payment');

                        return $item->payment ? $item->payment->payment_channel->label() : '-';
                    })
                    ->title(trans('plugins/car-rentals::booking.payment_method')),
                FormattedColumn::make('payment_id')
                    ->orderable(false)
                    ->searchable(false)
                    ->getValueUsing(function (FormattedColumn $column) {
                        $item = $column->getItem();

                        if (! is_plugin_active('payment')) {
                            return '&mdash;';
                        }

                        return $item->payment ? $item->payment->status->toHtml() : '-';
                    })
                    ->title(trans('plugins/car-rentals::booking.payment_status')),
                StatusColumn::make(),
            ])
            ->queryUsing(function (Builder $query): void {
                $query
                    ->select([
                        'id',
                        'customer_name',
                        'customer_id',
                        'currency_id',
                        'payment_id',
                        'amount',
                        'status',
                        'created_at',
                    ])
                    ->with('car', 'customer', 'currency');

                if (is_plugin_active('payment')) {
                    $query->with('payment');
                }
            });
    }
}
