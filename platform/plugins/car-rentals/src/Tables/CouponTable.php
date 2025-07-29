<?php

namespace Botble\CarRentals\Tables;

use Botble\CarRentals\Enums\CouponTypeEnum;
use Botble\CarRentals\Models\Coupon;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\Columns\DateTimeColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;

class CouponTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Coupon::class)
            ->addHeaderAction(CreateHeaderAction::make()->route('car-rentals.coupons.create'))
            ->addColumns([
                IdColumn::make(),
                FormattedColumn::make('code')->alignCenter()
                    ->addClass('block_coupon_code')
                    ->getValueUsing(function (FormattedColumn $column) {
                        $item = $column->getItem();

                        return sprintf('<b>%s</b>', $item->code);
                    }),
                FormattedColumn::make('value')->alignCenter()
                    ->getValueUsing(function (FormattedColumn $column) {
                        $item = $column->getItem();
                        /**
                         * @var CouponTypeEnum $type
                         */
                        $type = $item->type;

                        return $type->formatValue($item->value);
                    }),
                DateTimeColumn::make('expires_at')->dateFormat('Y-m-d H:s')->alignCenter(),
                FormattedColumn::make('limit')
                    ->alignCenter()
                    ->getValueUsing(fn (FormattedColumn $column) => $column->getItem()->limit ?: '∞'),
                FormattedColumn::make('used')
                    ->alignCenter()
                    ->getValueUsing(fn (FormattedColumn $column) => number_format($column->getItem()->used)),
            ])
            ->addActions([
                EditAction::make()->route('car-rentals.coupons.edit'),
                DeleteAction::make()->route('car-rentals.coupons.destroy'),
            ])->queryUsing(function (Builder $query): void {
                $query->select([
                    'id',
                    'code',
                    'value',
                    'type',
                    'expires_at',
                    'limit',
                    'used',
                ]);
            });
    }
}
