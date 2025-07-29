@extends(BaseHelper::getAdminMasterLayoutTemplate())

@push('header-action')
    <x-core::button
        type="button"
        color="primary"
        :outlined="true"
        class="date-range-picker"
        data-format-value="{{ trans('plugins/car-rentals::booking-reports.date_range_format_value', ['from' => '__from__', 'to' => '__to__']) }}"
        data-format="{{ Str::upper(config('core.base.general.date_format.js.date')) }}"
        data-href="{{ route('car-rentals.booking.reports.index') }}"
        data-start-date="{{ $startDate }}"
        data-end-date="{{ $endDate }}"
        icon="ti ti-calendar"
    >
        {{ trans('plugins/car-rentals::booking-reports.date_range_format_value', [
            'from' => BaseHelper::formatDate($startDate),
            'to' => BaseHelper::formatDate($endDate),
        ]) }}
    </x-core::button>
@endpush

@section('content')
    <div id="report-stats-content">
        @include('plugins/car-rentals::reports.ajax')
    </div>
@endsection

@push('footer')
    <script>
        var BotbleVariables = BotbleVariables || {};
        BotbleVariables.languages = BotbleVariables.languages || {};
        BotbleVariables.languages.reports = {!! json_encode(trans('plugins/car-rentals::booking-reports.ranges'), JSON_HEX_APOS) !!}
    </script>
@endpush