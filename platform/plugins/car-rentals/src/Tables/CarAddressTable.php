<?php

namespace Botble\CarRentals\Tables;

use Botble\CarRentals\Models\CarAddress;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Table\Columns\UpdatedAtColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;

class CarAddressTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(CarAddress::class)
            ->addHeaderAction(CreateHeaderAction::make()->route('car-rentals.car-addresses.create'))
            ->addActions([
                EditAction::make()->route('car-rentals.car-addresses.edit'),
                DeleteAction::make()->route('car-rentals.car-addresses.destroy'),
            ])
            ->addColumns([
                IdColumn::make(),
                FormattedColumn::make('detail_address')
                    ->getValueUsing(fn (FormattedColumn $column) => $column->getItem()->full_address)
                    ->title(trans('plugins/car-rentals::car-rentals.attribute.address.form.full_address')),
                StatusColumn::make('status'),
                CreatedAtColumn::make(),
                UpdatedAtColumn::make(),
            ])
            ->addBulkActions([
                DeleteBulkAction::make()->permission('car-rentals.car-addresses.destroy'),
            ])
            ->queryUsing(function (Builder $query): void {
                $query->select([
                    'id',
                    'detail_address',
                    'city_id',
                    'state_id',
                    'country_id',
                    'status',
                    'created_at',
                    'updated_at',
                ]);
            });
    }
}
