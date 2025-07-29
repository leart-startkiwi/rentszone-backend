<?php

namespace Botble\CarRentals\Tables\Vendor;

use Botble\CarRentals\Tables\MessageTable as BaseMessageTable;
use Botble\CarRentals\Tables\Traits\ForVendor;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\EmailColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\StatusColumn;
use Illuminate\Database\Eloquent\Builder;

class MessageTable extends BaseMessageTable
{
    use ForVendor;

    public function setup(): void
    {
        parent::setup();

        $this
            ->addActions([
                EditAction::make()->route('car-rentals.vendor.message.edit'),
                DeleteAction::make()->route('car-rentals.vendor.message.destroy'),
            ])
            ->removeColumns()
            ->removeAllBulkActions()
            ->removeAllBulkChanges()
            ->addColumns([
                IdColumn::make(),
                NameColumn::make()->route('car-rentals.message.edit'),
                EmailColumn::make(),
                Column::make('phone')
                    ->title(trans('plugins/car-rentals::message.phone')),
                Column::make('ip_address')
                    ->title(trans('plugins/car-rentals::message.ip_address')),
                CreatedAtColumn::make(),
                StatusColumn::make(),
            ])
            ->queryUsing(function (Builder $query) {
                $query
                    ->select([
                        'id',
                        'name',
                        'phone',
                        'email',
                        'created_at',
                        'ip_address',
                        'status',
                    ])
                    ->where('vendor_id', auth('customer')->id());
            });
    }

    public function columns(): array
    {
        return [];
    }

    public function bulkActions(): array
    {
        return [];
    }

    public function getBulkChanges(): array
    {
        return [];
    }
}
