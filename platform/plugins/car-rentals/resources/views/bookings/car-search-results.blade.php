<div class="row row-cards">
    @if(count($cars) > 0)
        @foreach($cars as $car)
            <div class="col-md-4 mb-3">
                <div class="card card-sm car-card">
                    <div class="card-status-top bg-primary"></div>
                    <div class="card-header">
                        <h3 class="card-title text-truncate" title="{{ $car->name }}">{{ $car->name }}</h3>
                    </div>
                    <div class="img-responsive img-responsive-21x9 card-img-top border-0" style="background-image: url('{{ RvMedia::getImageUrl($car->image, 'thumb', false, RvMedia::getDefaultImage()) }}')"></div>
                    <div class="card-body">
                        <div class="car-details mb-3">
                            <div class="d-flex align-items-center">
                                <div class="text-secondary me-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-currency-dollar" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2"></path>
                                        <path d="M12 3v3m0 12v3"></path>
                                    </svg>
                                </div>
                                <div>
                                    <strong>{{ format_price($car->rental_rate) }}</strong> / {{ __('day') }}
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary w-100 select-car-button" data-id="{{ $car->id }}" data-name="{{ $car->name }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M5 12l5 5l10 -10"></path>
                            </svg>
                            {{ __('Select Car') }}
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="col-12">
            <div class="empty">
                <div class="empty-img">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-car-off" width="128" height="128" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                        <path d="M15.584 15.588a2 2 0 0 0 2.828 2.83"></path>
                        <path d="M5 17h-2v-6l2 -5h1m4 0h4l4 5h1a2 2 0 0 1 2 2v4m-6 0h-6m-6 -6h8m4 0h3m-6 -3v-2"></path>
                        <path d="M3 3l18 18"></path>
                    </svg>
                </div>
                <p class="empty-title">{{ __('No cars available') }}</p>
                <p class="empty-subtitle text-secondary">
                    {{ __('No cars available for the selected dates. Try selecting different dates.') }}
                </p>
            </div>
        </div>
    @endif
</div>

<style>
    .car-card {
        transition: all 0.3s ease;
    }
    .car-card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .car-card.selected-car {
        border: 3px solid var(--tblr-primary);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    .car-card.selected-car .card-status-top {
        background-color: var(--tblr-success) !important;
    }
    .car-card.selected-car .select-car-button {
        background-color: var(--tblr-success);
        border-color: var(--tblr-success);
    }
    .img-responsive {
        height: 160px;
        background-position: center;
        background-size: cover;
        background-repeat: no-repeat;
    }
</style>
