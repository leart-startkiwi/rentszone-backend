<?php

namespace Botble\CarRentals\Tables;

use Botble\CarRentals\Models\Invoice;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\StatusColumn;
use Illuminate\Database\Eloquent\Builder;

class InvoiceTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Invoice::class)
            ->addBulkActions([
                DeleteBulkAction::make()->permission('car-rentals.invoices.destroy'),
            ])
            ->addActions([
                EditAction::make()->route('car-rentals.invoices.edit'),
                DeleteAction::make()->route('car-rentals.invoices.destroy'),
            ])
            ->addColumns([
                IdColumn::make(),
                NameColumn::make('customer_name')->route('car-rentals.invoices.edit'),
                Column::make('code'),
                FormattedColumn::make('amount')
                    ->label(trans('plugins/car-rentals::booking.amount'))
                    ->getValueUsing(function (FormattedColumn $column) {
                        $item = $column->getItem();
                        $item->loadMissing('currency');

                        return format_price($item->amount, $item->currency);
                    }),
                CreatedAtColumn::make(),
                StatusColumn::make(),
            ])
            ->queryUsing(function (Builder $query): void {
                $query->select([
                    'id',
                    'customer_name',
                    'code',
                    'amount',
                    'status',
                    'created_at',
                ]);
            });
    }
}
