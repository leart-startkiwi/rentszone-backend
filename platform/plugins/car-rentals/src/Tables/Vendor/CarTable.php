<?php

namespace Botble\CarRentals\Tables\Vendor;

use Botble\Base\Facades\Html;
use Botble\CarRentals\Enums\CarPurposeEnum;
use Botble\CarRentals\Facades\CarRentalsHelper;
use Botble\CarRentals\Models\Customer;
use Botble\CarRentals\Tables\CarTable as BaseCarTable;
use Botble\CarRentals\Tables\Traits\ForVendor;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;

class CarTable extends BaseCarTable
{
    use ForVendor;

    public function setup(): void
    {
        parent::setup();

        $this
            ->removeColumns()
            ->removeAllActions()
            ->removeHeaderActions()
            ->addHeaderAction(
                CreateHeaderAction::make()
                    ->route('car-rentals.vendor.cars.create')
            )
            ->addActions([
                EditAction::make()->route('car-rentals.vendor.cars.edit'),
                DeleteAction::make()->route('car-rentals.vendor.cars.destroy'),
            ])
            ->addColumns(function () {
                $columns = [
                    IdColumn::make(),
                    NameColumn::make()->route('car-rentals.vendor.cars.edit'),
                    FormattedColumn::make('license_plate')
                        ->title(trans('plugins/car-rentals::car-rentals.car.forms.license_plate'))
                        ->withEmptyState(),
                    FormattedColumn::make('make')
                        ->title(trans('plugins/car-rentals::car-rentals.car.forms.make'))
                        ->getValueUsing(function (FormattedColumn $column) {
                            return $column->getItem()->make?->name;
                        })
                        ->withEmptyState()
                        ->orderable(false)
                        ->searchable(false),
                    FormattedColumn::make('year')
                        ->title(trans('plugins/car-rentals::car-rentals.car.forms.year'))
                        ->withEmptyState(),
                    FormattedColumn::make('car_purpose')
                        ->title(trans('plugins/car-rentals::car-rentals.car.forms.car_purpose'))
                        ->renderUsing(function (FormattedColumn $column) {
                            $purpose = $column->getItem()->car_purpose;

                            return (new CarPurposeEnum())->make($purpose)->toHtml();
                        })
                        ->orderable(false)
                        ->searchable(false),
                    FormattedColumn::make('rental_rate')
                        ->title(trans('plugins/car-rentals::car-rentals.car.forms.rental_rate'))
                        ->renderUsing(function (FormattedColumn $column) {
                            return Html::tag('strong', format_price($column->getItem()->rental_rate));
                        }),
                    StatusColumn::make('status'),
                    CreatedAtColumn::make(),
                ];

                if (CarRentalsHelper::isEnabledPostApproval()) {
                    $columns[] = FormattedColumn::make('moderation_status')
                        ->title(trans('plugins/car-rentals::car-rentals.car.forms.moderation_status'))
                        ->width(150)
                        ->renderUsing(function (FormattedColumn $column) {
                            return $column->getItem()->moderation_status->toHtml();
                        });
                }

                return $columns;
            })
            ->queryUsing(function (Builder $query): void {
                $query
                    ->select([
                        'id',
                        'license_plate',
                        'make_id',
                        'name',
                        'year',
                        'mileage',
                        'rental_rate',
                        'status',
                        'moderation_status',
                        'reject_reason',
                        'insurance_info',
                        'vin',
                        'created_at',
                        'is_for_sale',
                    ])
                    ->with('make')
                    ->where('author_type', Customer::class)
                    ->where('author_id', auth('customer')->id());
            });
    }

    public function bulkActions(): array
    {
        return [];
    }

    public function getBulkChanges(): array
    {
        return [];
    }

    public function hasBulkActions(): bool
    {
        return false;
    }
}
