<?php

namespace Botble\CarRentals\Tables;

use Botble\CarRentals\Models\CarCategory;
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

class CarCategoryTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(CarCategory::class)
            ->addHeaderAction(CreateHeaderAction::make()->route('car-rentals.car-categories.create'))
            ->addActions([
                EditAction::make()->route('car-rentals.car-categories.edit'),
                DeleteAction::make()->route('car-rentals.car-categories.destroy'),
            ])
            ->addColumns([
                IdColumn::make(),
                NameColumn::make()->route('car-rentals.car-categories.edit'),
                StatusColumn::make(),
                CreatedAtColumn::make(),
            ])
            ->addBulkActions([
                DeleteBulkAction::make()->permission('car-rentals.car-categories.destroy'),
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
