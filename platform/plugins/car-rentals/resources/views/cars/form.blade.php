@extends('core/base::forms.form')

@section('form_end')
    @if ($form->getModel()->id)
        {!! Form::modalAction(
        'add-service-history-modal',
        trans('plugins/car-rentals::car-rentals.car.maintenance_history.forms.add_maintenance_history'),
        'info',
        \Botble\CarRentals\Forms\CarMaintenanceHistoryForm::createFromArray(['car' => $form->getModel()])
            ->setUrl(route(is_in_admin(true) ? 'car-rentals.car-maintenance-histories.store' : 'car-rentals.vendor.car-maintenance-histories.store', ['car' => $form->getModel()]))
            ->renderForm(),
        'confirm-add-entity-button',
        trans('plugins/car-rentals::car-rentals.car.maintenance_history.forms.add'),
        'modal-md',
    ) !!}

        <x-core::modal
                id="edit-service-entity-modal"
                :title="trans('plugins/car-rentals::car-rentals.car.maintenance_history.forms.edit_maintenance_history')"
        >
            <x-core::loading />
            <x-slot:footer>
                <x-core::button
                        data-bs-dismiss="modal"
                >
                    {{ trans('core/base::base.close') }}
                </x-core::button>

                <x-core::button
                        class="ms-auto"
                        color="primary"
                        data-bb-toggle="confirm-edit-entity-button"
                >
                    {{ trans('plugins/car-rentals::car-rentals.car.maintenance_history.forms.edit') }}
                </x-core::button>
            </x-slot:footer>
        </x-core::modal>
    @endif


    <x-core::modal.action
            type="danger"
            id="modal-confirm-delete"
            :title="trans('core/base::tables.confirm_delete')"
            :description="trans('core/base::tables.confirm_delete_msg')"
            :submit-button-label="trans('core/base::tables.delete')"
            :submit-button-attrs="['data-bb-toggle' => 'confirm-delete']"
    />

    @if($form->getModel()?->is_pending_moderation)
        <x-core::modal.action
            id="approve-car-modal"
            type="success"
            :title="trans('plugins/car-rentals::car-rentals.car.forms.status_moderation.approve_title')"
            :description="trans('plugins/car-rentals::car-rentals.car.forms.status_moderation.approve_message')"
            :form-action="route('car-rentals.cars.approve', $form->getModel())"
        >
            <x-slot:footer>
                <div class="w-100">
                    <div class="row">
                        <div class="col">
                            <x-core::button type="submit" color="success" class="w-100">
                                {{ trans('core/base::tables.submit') }}
                            </x-core::button>
                        </div>
                        <div class="col">
                            <x-core::button type="button" class="w-100" data-bs-dismiss="modal">
                                {{ trans('core/base::base.close') }}
                            </x-core::button>
                        </div>
                    </div>
                </div>
            </x-slot:footer>
        </x-core::modal.action>

        <x-core::modal.action
            id="reject-car-modal"
            type="danger"
            :title="trans('plugins/car-rentals::car-rentals.car.forms.status_moderation.reject_title')"
            :form-action="route('car-rentals.cars.reject', $form->getModel())"
        >
            <div class="text-muted">{{ trans('plugins/car-rentals::car-rentals.car.forms.status_moderation.reject_message') }}</div>

            <textarea
                name="reason"
                class="form-control mt-3"
                placeholder="{{ trans('plugins/car-rentals::car-rentals.car.forms.status_moderation.reject_reason') }}"
            ></textarea>

            <x-slot:footer>
                <div class="w-100">
                    <div class="row">
                        <div class="col">
                            <x-core::button type="submit" color="danger" class="w-100">
                                {{ trans('core/base::tables.submit') }}
                            </x-core::button>
                        </div>
                        <div class="col">
                            <x-core::button type="button" class="w-100" data-bs-dismiss="modal">
                                {{ trans('core/base::base.close') }}
                            </x-core::button>
                        </div>
                    </div>
                </div>
            </x-slot:footer>
        </x-core::modal.action>
    @endif
@stop
