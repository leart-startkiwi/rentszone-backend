<?php

namespace Botble\CarRentals\Http\Requests\Settings;

use Botble\Base\Rules\OnOffRule;
use Botble\Support\Http\Requests\Request;

class TaxSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'tax_enabled' => new OnOffRule(),
            'tax_active_ids' => ['nullable', 'array'],
            'tax_active_ids.*' => ['string', 'exists:cr_taxes,id'],
        ];
    }
}
