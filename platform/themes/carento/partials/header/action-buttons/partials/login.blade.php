@if (is_plugin_active('car-rentals'))
    @php
        $auth = auth('customer');
    @endphp

    @if (! $auth->check())
        <a href="{{ route('customer.login') }}" class="btn btn-signin neutral-1000 header-login-btn me-3">
            <svg class="mb-1" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                <path d="M1 12C1 12 0 12 0 11C0 10 1 7 6 7C11 7 12 10 12 11C12 12 11 12 11 12H1ZM6 6C6.79565 6 7.55871 5.68393 8.12132 5.12132C8.68393 4.55871 9 3.79565 9 3C9 2.20435 8.68393 1.44129 8.12132 0.87868C7.55871 0.316071 6.79565 0 6 0C5.20435 0 4.44129 0.316071 3.87868 0.87868C3.31607 1.44129 3 2.20435 3 3C3 3.79565 3.31607 4.55871 3.87868 5.12132C4.44129 5.68393 5.20435 6 6 6Z" fill="currentColor"></path>
            </svg>
            {{ __('Sign in') }}
        </a>
    @else
        @php $customer = $auth->user(); @endphp

        <div class="dropdown dropdown-login me-3">
            <a href="#" class="btn-login neutral-1000 dropdown-toggle" id="shareDropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                <div class="wrapper-avatar-ratio">
                    <div class="wrapper-avatar">
                        {{ RvMedia::image($customer->avatar_url, $customer->name, 'thumb', attributes: ['class' => 'avatar']) }}
                    </div>
                </div>
                <span class="user-name d-none d-sm-inline text-truncate">{{ $customer->name }}</span>
            </a>

            <ul class="dropdown-menu" aria-labelledby="shareDropdown">
                @if (CarRentalsHelper::isMultiVendorEnabled() && $customer->is_vendor)
                    <li>
                        <a
                            href="{{ route('car-rentals.vendor.dashboard') }}"
                            class="dropdown-item"
                        >
                            <span>{{ __('Vendor Dashboard') }}</span>
                        </a>
                    </li>
                @endif
                <li>
                    <a
                        href="{{ route('customer.bookings') }}"
                        class="dropdown-item"
                    >
                        <span>{{ __('My Bookings') }}</span>
                    </a>
                </li>
                <li>
                    <a
                        href="{{ route('customer.profile') }}"
                        class="dropdown-item"
                    >
                        <span>{{ __('Profile') }}</span>
                    </a>
                </li>
                <li>
                    <a
                        href="{{ route('customer.change-password') }}"
                        class="dropdown-item"
                    >
                        <span>{{ __('Change Password') }}</span>
                    </a>
                </li>
                <li>
                    <a
                        href="{{ route('customer.logout') }}"
                        class="dropdown-item"
                    >
                        <span>{{ __('Logout') }}</span>
                    </a>
                </li>
            </ul>
        </div>
    @endif

    @if (CarRentalsHelper::isMultiVendorEnabled())
        <a class="btn btn-signin bg-white text-dark" href="{{ auth('customer')->check() ? route('car-rentals.vendor.cars.create') : route('customer.login') }}">{{ __('Add Listing') }}</a>
    @endif
@endif
