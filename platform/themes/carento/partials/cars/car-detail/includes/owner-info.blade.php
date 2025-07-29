@if($car->author && $car->author->id)
<div class="group-collapse-expand">
    <div class="collapse show" id="collapseOwnerInfo">
        <div class="card card-body">
            <div class="row">
                <div class="col-md-12 mb-4">
                    <div class="d-flex align-items-center">
                        <div class="owner-avatar me-3">
                            <img src="{{ $car->author->avatar_url }}" alt="{{ $car->author->name }}" class="rounded-circle" width="60" height="60">
                        </div>
                        <div>
                            <h5 class="mb-1">{{ $car->author->name }}</h5>
                            <p class="text-muted mb-0">{{ __('Car Owner') }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="d-flex align-items-center">
                        <div class="feature-image me-3">
                            <img src="{{ Theme::asset()->url('images/icons/calendar.svg') }}" alt="{{ __('Owner Since') }}">
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="text-sm-bold me-2">{{ __('Joined Since') }}:</span>
                            <span class="text-md-regular">{{ $car->author->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                </div>

                @if($car->author->phone)
                <div class="col-md-6 mb-3">
                    <div class="d-flex align-items-center">
                        <div class="feature-image me-3">
                            <img src="{{ Theme::asset()->url('images/icons/phone-black.svg') }}" alt="{{ __('Phone') }}">
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="text-sm-bold me-2">{{ __('Phone') }}:</span>
                            <a href="tel:{{ $car->author->phone }}" class="text-md-regular">{{ $car->author->phone }}</a>
                        </div>
                    </div>
                </div>
                @endif

                @if($car->author->email)
                <div class="col-md-6 mb-3">
                    <div class="d-flex align-items-center">
                        <div class="feature-image me-3">
                            <img src="{{ Theme::asset()->url('images/icons/email-black.svg') }}" alt="{{ __('Email') }}">
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="text-sm-bold me-2">{{ __('Email') }}:</span>
                            <a href="mailto:{{ $car->author->email }}" class="text-md-regular">{{ $car->author->email }}</a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
