<?php

namespace Botble\CarRentals\Tables;

use Botble\CarRentals\Models\CarType;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\ImageColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;

class CarTypeTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(CarType::class)
            ->addHeaderAction(CreateHeaderAction::make()->route('car-rentals.car-types.create'))
            ->addActions([
                EditAction::make()->route('car-rentals.car-types.edit'),
                DeleteAction::make()->route('car-rentals.car-types.destroy'),
            ])
            ->addColumns([
                IdColumn::make(),
                NameColumn::make()->route('car-rentals.car-types.edit'),
                ImageColumn::make('image'),
                StatusColumn::make(),
                CreatedAtColumn::make(),
            ])
            ->addBulkActions([
                DeleteBulkAction::make()->permission('car-rentals.car-types.destroy'),
            ])
            ->queryUsing(function (Builder $query): void {
                $query->select([
                    'id',
                    'name',
                    'image',
                    'status',
                    'created_at',
                ]);
            });
    }
}
