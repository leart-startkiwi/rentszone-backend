<form action="{{ $shortcode->form_url ? url($shortcode->form_url) : 'javascript:void(0)' }}" class="form-contact car-loan-form" data-url="{{ route('public.calculate-loan-car') }}">
    <input type="hidden" name="currency" value="{{ $currency }}">

    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label class="text-sm-medium neutral-1000">{{ __('Vehicle Price (:currency)', ['currency' => $currency]) }}</label>
                <input class="form-control" name="vehicle_price" type="number" min="0" placeholder="{{ number_format(200000) }}" value="100000" />
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="text-sm-medium neutral-1000">{{ __('Annual Interest Rate (%)') }}</label>
                <input class="form-control" name="annual_interest_rate" type="number" min="0" placeholder="5" value="10" />
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="text-sm-medium neutral-1000">{{ __('Loan Term') }}</label>
                <select class="form-select" name="loan_term" id="loanTerm">
                    <optgroup label="{{ __('Short Term') }}">
                        <option value="12">{{ __(':months months (1 year)', ['months' => 12]) }}</option>
                        <option value="24">{{ __(':months months (:years years)', ['months' => 24, 'years' => 2]) }}</option>
                        <option value="36">{{ __(':months months (:years years)', ['months' => 36, 'years' => 3]) }}</option>
                    </optgroup>

                    <optgroup label="{{ __('Medium Term') }}">
                        <option value="48">{{ __(':months months (:years years)', ['months' => 48, 'years' => 4]) }}</option>
                        <option value="60">{{ __(':months months (:years years)', ['months' => 60, 'years' => 5]) }}</option>
                        <option value="72">{{ __(':months months (:years years)', ['months' => 72, 'years' => 6]) }}</option>
                    </optgroup>

                    <optgroup label="{{ __('Long Term') }}">
                        <option value="84">{{ __(':months months (:years years)', ['months' => 84, 'years' => 7]) }}</option>
                        <option value="96">{{ __(':months months (:years years)', ['months' => 96, 'years' => 8]) }}</option>
                    </optgroup>
                </select>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="text-sm-medium neutral-1000">{{ __('Down Payment (:currency)', ['currency' => $currency]) }}</label>
                <input class="form-control" name="down_payment" type="number" min="0" placeholder="{{ number_format(120000) }}" value="20000"/>
            </div>
        </div>
        <div class="loan-form-error text-danger" style="display: none">

        </div>
        <div class="loan-form-result">
            <div class="row py-4">
                <div class="col-md-7 col-8 d-flex flex-column gap-1">
                    <p class="text-sm-bold neutral-1000">{{ __('Down Payment Amount') }}</p>
                    <p class="text-sm-bold neutral-1000">{{ __('Loan Amount') }}</p>
                    <p class="text-sm-bold neutral-1000">{{ __('Estimated Monthly Payment') }}</p>
                </div>
                <div class="col-md-5 col-4 d-flex flex-column gap-1 align-items-end align-items-md-start">
                    <p class="text-sm-bold neutral-1000 down-payment-amount">0</p>
                    <p class="text-sm-bold neutral-1000 loan-amount">0</p>
                    <p class="text-sm-bold text-primary-dark est-monthly-payment">0</p>
                </div>
            </div>
        </div>
        @if ($shortcode->form_url)
            <div class="col-lg-12">
                <button  class="btn btn-book">
                    {!! BaseHelper::clean($shortcode->form_button_label ?: __('Apply for a loan')) !!}
                    <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8.5 15L15.5 8L8.5 1M15.5 8L1.5 8" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </button>
            </div>
        @endif
    </div>
</form>
