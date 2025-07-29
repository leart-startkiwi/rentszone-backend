<div class="card h-100">
    <div class="card-header">
        <h3 class="card-title">{{ trans('plugins/car-rentals::booking-reports.booking_status_distribution') }}</h3>
    </div>
    <div class="card-body">
        <div id="booking-status-chart" class="chart-lg mb-4"></div>
        <h4 class="mb-3">{{ trans('plugins/car-rentals::booking-reports.status_breakdown') }}</h4>
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-success text-white avatar">
                                    <x-core::icon name="ti ti-check" />
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    {{ $completedBookings }}
                                </div>
                                <div class="text-muted">
                                    {{ trans('plugins/car-rentals::booking.statuses.completed') }}
                                </div>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 6px;">
                            <div class="progress-bar bg-success" role="progressbar"
                                style="width: {{ $bookingsCount > 0 ? ($completedBookings / $bookingsCount) * 100 : 0 }}%"
                                aria-valuenow="{{ $bookingsCount > 0 ? ($completedBookings / $bookingsCount) * 100 : 0 }}"
                                aria-valuemin="0"
                                aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-azure text-white avatar">
                                    <x-core::icon name="ti ti-loader" />
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    {{ $processingBookings }}
                                </div>
                                <div class="text-muted">
                                    {{ trans('plugins/car-rentals::booking.statuses.processing') }}
                                </div>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 6px;">
                            <div class="progress-bar bg-azure" role="progressbar"
                                style="width: {{ $bookingsCount > 0 ? ($processingBookings / $bookingsCount) * 100 : 0 }}%"
                                aria-valuenow="{{ $bookingsCount > 0 ? ($processingBookings / $bookingsCount) * 100 : 0 }}"
                                aria-valuemin="0"
                                aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-warning text-white avatar">
                                    <x-core::icon name="ti ti-clock" />
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    {{ $pendingBookings }}
                                </div>
                                <div class="text-muted">
                                    {{ trans('plugins/car-rentals::booking.statuses.pending') }}
                                </div>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 6px;">
                            <div class="progress-bar bg-warning" role="progressbar"
                                style="width: {{ $bookingsCount > 0 ? ($pendingBookings / $bookingsCount) * 100 : 0 }}%"
                                aria-valuenow="{{ $bookingsCount > 0 ? ($pendingBookings / $bookingsCount) * 100 : 0 }}"
                                aria-valuemin="0"
                                aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-danger text-white avatar">
                                    <x-core::icon name="ti ti-x" />
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    {{ $cancelledBookings }}
                                </div>
                                <div class="text-muted">
                                    {{ trans('plugins/car-rentals::booking.statuses.cancelled') }}
                                </div>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 6px;">
                            <div class="progress-bar bg-danger" role="progressbar"
                                style="width: {{ $bookingsCount > 0 ? ($cancelledBookings / $bookingsCount) * 100 : 0 }}%"
                                aria-valuenow="{{ $bookingsCount > 0 ? ($cancelledBookings / $bookingsCount) * 100 : 0 }}"
                                aria-valuemin="0"
                                aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('footer')
<script>
    $(document).ready(function() {
        new ApexCharts(document.getElementById('booking-status-chart'), {
            series: [{{ $completedBookings }}, {{ $processingBookings }}, {{ $pendingBookings }}, {{ $cancelledBookings }}],
            chart: {
                height: 300,
                type: 'donut',
                fontFamily: 'inherit',
            },
            labels: [
                '{{ trans('plugins/car-rentals::booking.statuses.completed') }}',
                '{{ trans('plugins/car-rentals::booking.statuses.processing') }}',
                '{{ trans('plugins/car-rentals::booking.statuses.pending') }}',
                '{{ trans('plugins/car-rentals::booking.statuses.cancelled') }}'
            ],
            colors: ['#4caf50', '#0ca6e9', '#ffc107', '#f44336'],
            legend: {
                show: true,
                position: 'bottom',
                offsetY: 12,
                markers: {
                    width: 10,
                    height: 10,
                    radius: 100,
                },
                itemMargin: {
                    horizontal: 8,
                    vertical: 8
                },
            },
            tooltip: {
                fillSeriesColor: false
            },
            dataLabels: {
                enabled: true,
                formatter: function(val, opts) {
                    return opts.w.config.series[opts.seriesIndex] + ' (' + Math.round(val) + '%)';
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%',
                        labels: {
                            show: true,
                            name: {
                                show: true,
                            },
                            value: {
                                show: true,
                                fontSize: '22px',
                                fontWeight: 600,
                                formatter: function(val) {
                                    return val;
                                }
                            },
                            total: {
                                show: true,
                                label: '{{ trans('plugins/car-rentals::booking-reports.total') }}',
                                formatter: function(w) {
                                    return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                }
                            }
                        }
                    }
                }
            }
        }).render();
    });
</script>
@endpush
