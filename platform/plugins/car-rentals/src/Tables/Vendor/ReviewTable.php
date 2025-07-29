<?php

namespace Botble\CarRentals\Tables\Vendor;

use Botble\CarRentals\Models\Car;
use Botble\CarRentals\Models\CarReview;
use Botble\CarRentals\Models\Customer;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\BulkChanges\CreatedAtBulkChange;
use Botble\Table\BulkChanges\SelectBulkChange;
use Botble\Table\BulkChanges\StatusBulkChange;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\StatusColumn;
use Illuminate\Database\Eloquent\Builder;

class ReviewTable extends TableAbstract
{
    protected int $vendorId = 0;

    public function setVendorId(int $vendorId): self
    {
        $this->vendorId = $vendorId;

        return $this;
    }

    public function setup(): void
    {
        $this
            ->model(CarReview::class)
            ->addColumns([
                IdColumn::make(),
                Column::make('car.name')
                    ->title(trans('plugins/car-rentals::car-rentals.review.forms.car')),
                Column::make('customer.name')
                    ->title(trans('plugins/car-rentals::car-rentals.review.forms.customer')),
                FormattedColumn::make('star')
                    ->title(trans('plugins/car-rentals::car-rentals.review.forms.star'))
                    ->getValueUsing(function (FormattedColumn $column) {
                        $item = $column->getItem();

                        if (! $item->star) {
                            return '-';
                        }

                        return view('plugins/car-rentals::reviews.partials.rating', ['star' => $item->star])->render();
                    }),
                Column::make('content')
                    ->title(trans('plugins/car-rentals::car-rentals.review.forms.content')),
                StatusColumn::make(),
                CreatedAtColumn::make(),
            ])
            ->addActions([
                DeleteAction::make()->route('car-rentals.vendor.reviews.destroy'),
            ])
            ->addBulkActions([
                DeleteBulkAction::make()->permission('car-rentals.vendor.reviews.destroy'),
            ])
            ->addBulkChanges([
                StatusBulkChange::make(),
                SelectBulkChange::make()
                    ->name('car_id')
                    ->title(trans('plugins/car-rentals::car-rentals.review.forms.car'))
                    ->searchable()
                    ->choices(fn () => Car::query()
                        ->where('author_type', Customer::class)
                        ->where('author_id', $this->vendorId)
                        ->pluck('name', 'id')
                        ->all()),
                SelectBulkChange::make()
                    ->name('star')
                    ->title(trans('plugins/car-rentals::car-rentals.review.forms.star'))
                    ->choices([
                        1 => trans('plugins/car-rentals::car-rentals.review.ratings.1'),
                        2 => trans('plugins/car-rentals::car-rentals.review.ratings.2'),
                        3 => trans('plugins/car-rentals::car-rentals.review.ratings.3'),
                        4 => trans('plugins/car-rentals::car-rentals.review.ratings.4'),
                        5 => trans('plugins/car-rentals::car-rentals.review.ratings.5'),
                    ]),
                CreatedAtBulkChange::make(),
            ])
            ->queryUsing(function (Builder $query): void {
                $query
                    ->select([
                        'id',
                        'car_id',
                        'customer_id',
                        'star',
                        'content',
                        'status',
                        'created_at',
                    ])
                    ->with(['car', 'customer'])
                    ->whereHas('car', function ($query) {
                        $query
                            ->where('author_type', Customer::class)
                            ->where('author_id', $this->vendorId);
                    });
            });
    }
}
