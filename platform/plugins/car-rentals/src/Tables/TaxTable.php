<?php

namespace Botble\CarRentals\Tables;

use Botble\CarRentals\Models\Tax;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\BulkChanges\CreatedAtBulkChange;
use Botble\Table\BulkChanges\NameBulkChange;
use Botble\Table\BulkChanges\StatusBulkChange;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;

class TaxTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Tax::class)
            ->addHeaderAction(CreateHeaderAction::make()->route('car-rentals.taxes.create'))
            ->addActions([
                EditAction::make()->route('car-rentals.taxes.edit'),
                DeleteAction::make()->route('car-rentals.taxes.destroy'),
            ])
            ->addColumns([
                IdColumn::make(),
                NameColumn::make('name')->route('car-rentals.taxes.edit'),
                FormattedColumn::make('percentage')->alignCenter()->append(fn () => '%'),
                StatusColumn::make('status'),
                FormattedColumn::make('priority')->alignCenter(),
                CreatedAtColumn::make(),
            ])
            ->addBulkActions([
                DeleteBulkAction::make()->permission('car-rentals.taxes.destroy'),
            ])
            ->addBulkChanges([
                NameBulkChange::make(),
                StatusBulkChange::make(),
                CreatedAtBulkChange::make(),
            ])
            ->queryUsing(function (Builder $query): void {
                $query->select([
                    'id',
                    'name',
                    'percentage',
                    'status',
                    'priority',
                    'created_at',
                ]);
            });
    }
}
