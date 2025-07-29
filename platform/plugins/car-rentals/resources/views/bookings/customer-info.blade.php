<div class="card card-sm">
    <div class="card-status-start bg-primary"></div>
    <div class="card-body">
        <div class="row align-items-center mb-3">
            <div class="col-auto">
                <span class="avatar avatar-md" style="background-image: url('{{ $customer->avatar_url }}')">
                    @if(!$customer->avatar)
                        {{ substr($customer->name, 0, 2) }}
                    @endif
                </span>
            </div>
            <div class="col">
                <h3 class="card-title mb-0">{{ $customer->name }}</h3>
                <div class="text-secondary">
                    <span class="badge bg-primary-lt">{{ __('Customer') }}</span>
                </div>
            </div>
        </div>

        <div class="list-group list-group-flush">
            <div class="list-group-item">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-mail text-secondary" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z"></path>
                            <path d="M3 7l9 6l9 -6"></path>
                        </svg>
                    </div>
                    <div class="col">
                        <div class="text-truncate">
                            <a href="mailto:{{ $customer->email }}">{{ $customer->email }}</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="list-group-item">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-phone text-secondary" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2"></path>
                        </svg>
                    </div>
                    <div class="col">
                        <div class="text-truncate">
                            <a href="tel:{{ $customer->phone }}">{{ $customer->phone }}</a>
                        </div>
                    </div>
                </div>
            </div>

            @if($customer->address)
            <div class="list-group-item">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-map-pin text-secondary" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path>
                            <path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z"></path>
                        </svg>
                    </div>
                    <div class="col">
                        <div class="text-truncate">
                            {{ $customer->address }}
                        </div>
                        @if($customer->city || $customer->state || $customer->zip)
                        <div class="text-secondary">
                            {{ $customer->city ? $customer->city . ', ' : '' }}
                            {{ $customer->state ? $customer->state . ', ' : '' }}
                            {{ $customer->country ? $customer->country . ' ' : '' }}
                            {{ $customer->zip ?? '' }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
