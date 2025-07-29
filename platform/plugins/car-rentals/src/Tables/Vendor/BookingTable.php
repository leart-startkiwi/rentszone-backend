<?php

namespace Botble\CarRentals\Tables\Vendor;

use Botble\CarRentals\Tables\BookingTable as BaseBookingTable;
use Botble\CarRentals\Tables\Traits\ForVendor;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\StatusColumn;
use Illuminate\Database\Eloquent\Builder;

class BookingTable extends BaseBookingTable
{
    use ForVendor;

    public function setup(): void
    {
        parent::setup();

        $this
            ->addActions([
                EditAction::make()->route('car-rentals.vendor.bookings.edit'),
                DeleteAction::make()->route('car-rentals.vendor.bookings.destroy'),
            ])
            ->removeColumns()
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

                        $item->loadMissing('customer');

                        return $item->customer ? $item->customer->name : '-';
                    }),
                FormattedColumn::make('amount')
                    ->label(trans('plugins/car-rentals::booking.amount'))
                    ->getValueUsing(function (FormattedColumn $column) {
                        $item = $column->getItem();

                        return format_price($item->amount, $item->currency);
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

                        return sprintf('%s - %s', $car->rental_start_date->format('d/m/Y'), $car->rental_end_date->format('d/m/Y'));
                    }),
                CreatedAtColumn::make(),
                FormattedColumn::make('payment_method')
                    ->orderable(false)
                    ->searchable(false)
                    ->getValueUsing(function (FormattedColumn $column) {
                        $item = $column->getItem();

                        if (! is_plugin_active('payment')) {
                            return '-';
                        }

                        $item->loadMissing('payment');

                        return $item->payment ? $item->payment->payment_channel->label() : '-';
                    })
                    ->title(trans('plugins/car-rentals::booking.payment_method')),
                FormattedColumn::make('payment_id')
                    ->getValueUsing(function (FormattedColumn $column) {
                        $item = $column->getItem();

                        if (! is_plugin_active('payment')) {
                            return '-';
                        }

                        $item->loadMissing('payment');

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
                    ->with('car', 'customer', 'currency', 'payment')
                    ->where('vendor_id', auth('customer')->id());
            });
    }

    public function bulkActions(): array
    {
        return [];
    }

    public function getBulkChanges(): array
    {
        return [];
    }

    public function hasBulkActions(): bool
    {
        return false;
    }
}
