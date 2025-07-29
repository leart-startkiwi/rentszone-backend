@php
    $customer = auth('customer')->user();
@endphp

<div class="container customer-page crop-avatar py-5">
    <div class="row wrapper-profile g-4">
        <div class="col-md-4 col-lg-3 d-md-block sidebar profile-sidebar">
            <div class="profile-card">
                <div class="text-center mb-4">
                    <form id="avatar-upload-form" enctype="multipart/form-data" action="javascript:void(0)" onsubmit="return false">
                        <div class="avatar-upload-container">
                            <div class="form-group mb-3">
                                <div id="account-avatar">
                                    <div class="profile-image custom-avatar-master">
                                        <div class="avatar-view mt-card-avatar">
                                            <img class="br2" src="{{ auth('customer')->user()->avatar_url }}" alt="{{ auth('customer')->user()->name }}" />
                                            <div class="avatar-bg-overlay"></div>
                                        </div>
                                        <x-core::icon name="ti ti-pencil" class="avatar-view" />
                                    </div>
                                </div>
                            </div>
                            <div id="print-msg" class="text-danger hidden"></div>
                        </div>
                    </form>
                    <h5 class="mb-1 fw-bold">{{ $customer->name }}</h5>
                    <p class="text-muted small">{{ $customer->email }}</p>
                </div>

                <div class="nav flex-column nav-dashboard">
                    <a href="{{ route('customer.overview') }}" @class(['nav-link', 'active' => Route::is('customer.overview')])>
                        <x-core::icon name="ti ti-user" class="me-2" />
                        <span>{{ __('Overview') }}</span>
                    </a>
                    <a href="{{ route('customer.profile') }}" @class(['nav-link', 'active' => Route::is('customer.profile')])>
                        <x-core::icon name="ti ti-settings" class="me-2" />
                        <span>{{ __('Profile') }}</span>
                    </a>
                    <a href="{{ route('customer.change-password') }}" @class(['nav-link', 'active' => Route::is('customer.change-password')])>
                        <x-core::icon name="ti ti-lock" class="me-2" />
                        <span>{{ __('Change password') }}</span>
                    </a>
                    <a href="{{ route('customer.bookings') }}" @class(['nav-link', 'active' => Route::is('customer.bookings')])>
                        <x-core::icon name="ti ti-calendar" class="me-2" />
                        <span>{{ __('My Bookings') }}</span>
                    </a>
                    <a href="{{ route('customer.reviews') }}" @class(['nav-link', 'active' => Route::is('customer.reviews')])>
                        <x-core::icon name="ti ti-star" class="me-2" />
                        <span>{{ __('My Reviews') }}</span>
                    </a>
                    <a href="{{ route('customer.logout') }}" class="nav-link">
                        <x-core::icon name="ti ti-logout" class="me-2" />
                        <span>{{ __('Logout') }}</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-8 col-lg-9 ms-sm-auto profile-content">
            <div class="content-card">
                <div class="card-header">
                    <h1 class="card-title h4 mb-0">{{ SeoHelper::getTitle() }}</h1>
                </div>
                <div class="card-body">
                    <div class="customer-content">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="avatar-modal" tabindex="-1" role="dialog" aria-labelledby="avatar-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form class="avatar-form" method="post" action="{{ route('customer.avatar') }}" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h4 class="modal-title" id="avatar-modal-label"><i class="til_img"></i><strong>{{ __('Profile Image') }}</strong></h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="avatar-body">
                            <div class="avatar-upload">
                                <input class="avatar-src" name="avatar_src" type="hidden" />
                                <input class="avatar-data" name="avatar_data" type="hidden" />
                                @csrf
                                <label class="form-label" for="avatarInput">{{ __('New image') }}</label>
                                <input class="avatar-input form-control" id="avatarInput" name="avatar_file" type="file" />
                            </div>

                            <div class="loading" style="display: none;" tabindex="-1" role="img" aria-label="{{ __('Loading') }}"></div>

                            <div class="row">
                                <div class="col-md-9">
                                    <div class="avatar-wrapper"></div>
                                    <div class="error-message text-danger" style="display: none;"></div>
                                </div>
                                <div class="col-md-3 avatar-preview-wrapper">
                                    <div class="avatar-preview preview-lg"></div>
                                    <div class="avatar-preview preview-md"></div>
                                    <div class="avatar-preview preview-sm"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button class="btn btn-primary avatar-save" type="submit">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
