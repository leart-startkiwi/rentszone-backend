@extends(CarRentalsHelper::viewPath('vendor-dashboard.layouts.master'))

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-core::card>
                <x-core::card.header>
                    <x-core::card.title>
                        {{ __('Create Withdrawal Request') }}
                    </x-core::card.title>
                </x-core::card.header>
                <x-core::card.body>
                    <div class="row">
                        <div class="col-md-6">
                            <x-core::form
                                :url="route('car-rentals.vendor.withdrawals.store')"
                                method="post"
                            >
                                <div class="mb-3">
                                    <label for="amount" class="form-label">{{ __('Amount') }}</label>
                                    <input
                                        type="number"
                                        class="form-control"
                                        id="amount"
                                        name="amount"
                                        min="{{ $minimumWithdrawal }}"
                                        max="{{ $maximum }}"
                                        step="0.01"
                                        placeholder="{{ __('Amount you want to withdraw') }}"
                                    >
                                    <small class="text-muted">{{ __('Minimum withdrawal amount is :amount', ['amount' => format_price($minimumWithdrawal)]) }}</small>
                                    <small class="text-muted d-block">{{ __('Maximum withdrawal amount is :amount', ['amount' => format_price($maximum)]) }}</small>
                                    <small class="text-muted d-block">{{ __('Fee: :amount', ['amount' => format_price($fee)]) }}</small>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">{{ __('Description') }}</label>
                                    <textarea
                                        class="form-control"
                                        id="description"
                                        name="description"
                                        rows="3"
                                        placeholder="{{ __('Description (optional)') }}"
                                    ></textarea>
                                </div>

                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">{{ __('Submit Request') }}</button>
                                </div>
                            </x-core::form>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-info">
                                <h4 class="alert-heading">{{ __('Information') }}</h4>
                                <p>{{ __('Current balance: :amount', ['amount' => format_price(auth('customer')->user()->balance)]) }}</p>
                                <p>{{ __('Fee: :amount', ['amount' => format_price($fee)]) }}</p>
                                <p>{{ __('You will receive: :amount', ['amount' => format_price($maximum)]) }}</p>
                                <hr>
                                <p class="mb-0">{{ __('The withdrawal request will be processed within 3-5 business days.') }}</p>
                            </div>
                        </div>
                    </div>
                </x-core::card.body>
            </x-core::card>
        </div>
    </div>
@endsection
