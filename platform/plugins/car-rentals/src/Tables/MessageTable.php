<?php

namespace Botble\CarRentals\Tables;

use Botble\CarRentals\Enums\MessageStatusEnum;
use Botble\CarRentals\Models\Message;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\BulkChanges\CreatedAtBulkChange;
use Botble\Table\BulkChanges\NameBulkChange;
use Botble\Table\BulkChanges\StatusBulkChange;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\EmailColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\StatusColumn;
use Illuminate\Database\Eloquent\Builder;

class MessageTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Message::class)
            ->addActions([
                EditAction::make()->route('car-rentals.message.edit'),
                DeleteAction::make()->route('car-rentals.message.destroy'),
            ])
            ->addBulkActions([
                DeleteBulkAction::make()->permission('car-rentals.message.destroy'),
            ])
            ->addBulkChanges([
                NameBulkChange::make(),
                StatusBulkChange::make()->choices(MessageStatusEnum::labels()),
                CreatedAtBulkChange::make(),
            ])
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
                    ]);
            });
    }
}
