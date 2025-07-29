<div class="pricing-summary mb-3">
    <div class="row">
        <div class="col-lg-8 col-6">{{ __('Subtotal') }}</div>
        <div class="col-lg-4 col-6 text-end"><strong>{{ format_price($subtotal ?? 0) }}</strong></div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-6"><span class="tax-label">{{ __('Taxes') }}</span> @if (!empty($taxInfo)) - <small class="text-muted tax-info-text">{{ $taxInfo }}</small>@endif</div>
        <div class="col-lg-4 col-6 text-end"><strong>{{ format_price($tax ?? 0) }}</strong></div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-6">{{ __('Sale discount') }}</div>
        <div class="col-lg-4 col-6 text-end"><strong>{{ format_price($discount ?? 0) }}</strong></div>
    </div>
    <div class="row total">
        <div class="col-lg-8 col-6">{{ __('Total') }}</div>
        <div class="col-lg-4 col-6 text-end"><strong>{{ format_price($total ?? 0) }}</strong></div>
    </div>
</div>
