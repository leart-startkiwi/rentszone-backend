<?php

namespace Botble\CarRentals\Tables;

use Botble\CarRentals\Models\CarMake;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\BulkChanges\CreatedAtBulkChange;
use Botble\Table\BulkChanges\NameBulkChange;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\ImageColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;

class CarMakeTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(CarMake::class)
            ->addHeaderAction(CreateHeaderAction::make()->route('car-rentals.car-makes.create'))
            ->addActions([
                EditAction::make()->route('car-rentals.car-makes.edit'),
                DeleteAction::make()->route('car-rentals.car-makes.destroy'),
            ])
            ->addColumns([
                IdColumn::make(),
                NameColumn::make('name')->route('car-rentals.car-makes.edit'),
                ImageColumn::make('logo')->title(trans('plugins/car-rentals::car-rentals.make.forms.logo')),
                StatusColumn::make('status'),
                CreatedAtColumn::make(),
            ])
            ->addBulkActions([
                DeleteBulkAction::make()->permission('car-rentals.car-makes.destroy'),
            ])
            ->addBulkChanges([
                NameBulkChange::make(),
                CreatedAtBulkChange::make(),
            ])
            ->queryUsing(function (Builder $query): void {
                $query->select([
                    'id',
                    'name',
                    'logo',
                    'status',
                    'created_at',
                ]);
            });
    }
}
