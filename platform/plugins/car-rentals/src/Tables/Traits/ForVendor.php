<?php

namespace Botble\CarRentals\Tables\Traits;

use Botble\CarRentals\Facades\CarRentalsHelper;

trait ForVendor
{
    public function booted(): void
    {
        $this
            ->setView(CarRentalsHelper::viewPath('vendor-dashboard.table.base'))
            ->bulkChangeUrl(route('car-rentals.vendor.table.bulk-change.save'))
            ->bulkChangeDataUrl(route('car-rentals.vendor.table.bulk-change.data'))
            ->bulkActionDispatchUrl(route('car-rentals.vendor.table.bulk-action.dispatch'))
            ->filterInputUrl(route('car-rentals.vendor.table.filter.input'));
    }
}
