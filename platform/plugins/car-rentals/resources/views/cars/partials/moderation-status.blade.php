@if ($model->is_pending_moderation)
    <div class="btn-list">
        <x-core::button type="button" color="success" icon="ti ti-check" size="sm" data-bs-toggle="modal" data-bs-target="#approve-car-modal">
            {{ trans('plugins/car-rentals::car-rentals.car.forms.status_moderation.approve') }}
        </x-core::button>
        <x-core::button type="button" color="danger" icon="ti ti-x" size="sm" data-bs-toggle="modal" data-bs-target="#reject-car-modal">
            {{ trans('plugins/car-rentals::car-rentals.car.forms.status_moderation.reject') }}
        </x-core::button>
    </div>
@else
    {!! BaseHelper::clean($model->moderation_status->toHtml()) !!}
    @if($model->moderation_status == \Botble\CarRentals\Enums\ModerationStatusEnum::REJECTED)
        <p class="mt-2 mb-0">
            <span class="text-muted">{{ trans('plugins/car-rentals::car-rentals.cars.forms.status_moderation.reason_rejected') }}: </span>
            <strong>{{ $model->reject_reason }}</strong>
        </p>
    @endif
@endif
