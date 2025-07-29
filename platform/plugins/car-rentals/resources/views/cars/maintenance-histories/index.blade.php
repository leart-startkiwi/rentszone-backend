<x-core::table>
    <x-core::table.header>
        <x-core::table.header.cell>
            #
        </x-core::table.header.cell>
        <x-core::table.header.cell>
            {{ trans('plugins/car-rentals::car-rentals.car.maintenance_history.forms.name') }}
        </x-core::table.header.cell>
        <x-core::table.header.cell>
            {{ trans('plugins/car-rentals::car-rentals.car.maintenance_history.forms.amount') }}
        </x-core::table.header.cell>
        <x-core::table.header.cell>
            {{ trans('plugins/car-rentals::car-rentals.car.maintenance_history.forms.date') }}
        </x-core::table.header.cell>
        <x-core::table.header.cell class="text-end">
            {{ trans('plugins/car-rentals::car-rentals.car.maintenance_history.forms.action') }}
        </x-core::table.header.cell>
    </x-core::table.header>
    <x-core::table.body>
        @forelse ($serviceHistories as $service)
            <x-core::table.body.row>
                <x-core::table.body.cell>
                    {{ $service->getKey() }}
                </x-core::table.body.cell>
                <x-core::table.body.cell>
                    {{ $service->name }}
                </x-core::table.body.cell>
                <x-core::table.body.cell>
                    {{ format_price($service->amount, $service->currency) }}
                </x-core::table.body.cell>
                <x-core::table.body.cell>
                    @if ($service->date)
                        {{ BaseHelper::formatDate($service->date) }}
                    @else
                        &mdash;
                    @endif
                </x-core::table.body.cell>
                <x-core::table.body.cell class="text-end">
                    <span data-bs-toggle="tooltip" title="{{ trans('plugins/car-rentals::car-rentals.car.maintenance_history.forms.edit') }}">
                        <x-core::button
                            tag="a"
                            color="primary"
                            size="sm"
                            data-bs-toggle="modal"
                            data-bs-target="#edit-service-entity-modal"
                            :data-modal-title="trans('plugins/car-rentals::car-rentals.car.maintenance_history.forms.edit_maintenance_history')"
                            data-table="#maintenance-histories-table"
                            :href="route(is_in_admin(true) ? 'car-rentals.car-maintenance-histories.edit' : 'car-rentals.vendor.car-maintenance-histories.edit', $service->id)"
                            icon="ti ti-edit"
                            :icon-only="true"
                        />
                    </span>
                    <span data-bs-toggle="tooltip" title="{{ trans('plugins/car-rentals::car-rentals.car.maintenance_history.forms.delete') }}">
                        <x-core::button
                            tag="a"
                            color="danger"
                            size="sm"
                            :href="route(is_in_admin(true) ? 'car-rentals.car-maintenance-histories.destroy' : 'car-rentals.vendor.car-maintenance-histories.destroy', $service->id)"
                            data-bs-toggle="modal"
                            data-table="#maintenance-histories-table"
                            data-bs-target="#modal-confirm-delete"
                            icon="ti ti-trash"
                            :icon-only="true"
                        />
                    </span>
                </x-core::table.body.cell>
            </x-core::table.body.row>
        @empty
            <x-core::table.body.row>
                <x-core::table.body.cell colspan="6" class="text-center text-muted">
                    {{ trans('plugins/car-rentals::car-rentals.car.maintenance_history.forms.no_maintenance_history') }}
                </x-core::table.body.cell>
            </x-core::table.body.row>
        @endforelse
    </x-core::table.body>
</x-core::table>
