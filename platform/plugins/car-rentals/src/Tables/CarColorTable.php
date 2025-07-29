<?php

namespace Botble\CarRentals\Tables;

use Botble\CarRentals\Models\CarColor;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;

class CarColorTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(CarColor::class)
            ->addHeaderAction(CreateHeaderAction::make()->route('car-rentals.car-colors.create'))
            ->addActions([
                EditAction::make()->route('car-rentals.car-colors.edit'),
                DeleteAction::make()->route('car-rentals.car-colors.destroy'),
            ])
            ->addColumns([
                IdColumn::make(),
                NameColumn::make('name')->route('car-rentals.car-colors.edit'),
                StatusColumn::make(),
                CreatedAtColumn::make(),
            ])
            ->addBulkActions([
                DeleteBulkAction::make()->permission('car-rentals.car-colors.destroy'),
            ])
            ->queryUsing(function (Builder $query): void {
                $query->select([
                    'id',
                    'name',
                    'status',
                    'created_at',
                ]);
            });
    }
}
