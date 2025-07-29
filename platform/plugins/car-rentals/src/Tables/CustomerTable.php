<?php

namespace Botble\CarRentals\Tables;

use Botble\CarRentals\Models\Customer;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\BulkChanges\CreatedAtBulkChange;
use Botble\Table\BulkChanges\NameBulkChange;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\EmailColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;

class CustomerTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Customer::class)
            ->addHeaderAction(CreateHeaderAction::make()->route('car-rentals.customers.create'))
            ->addActions([
                EditAction::make()->route('car-rentals.customers.edit'),
                DeleteAction::make()->route('car-rentals.customers.destroy'),
            ])
            ->addColumns([
                IdColumn::make(),
                NameColumn::make('name')->route('car-rentals.customers.edit'),
                EmailColumn::make(),
                Column::make('phone'),
                StatusColumn::make(),
                CreatedAtColumn::make(),
            ])
            ->addBulkActions([
                DeleteBulkAction::make()->permission('car-rentals.customers.destroy'),
            ])
            ->addBulkChanges([
                NameBulkChange::make(),
                CreatedAtBulkChange::make(),
            ])
            ->queryUsing(function (Builder $query): void {
                $query->select([
                    'id',
                    'name',
                    'avatar',
                    'email',
                    'phone',
                    'status',
                    'created_at',
                ]);
            });
    }
}
