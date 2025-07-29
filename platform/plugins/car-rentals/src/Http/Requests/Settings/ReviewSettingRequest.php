<?php

namespace Botble\CarRentals\Http\Requests\Settings;

use Botble\Base\Rules\OnOffRule;
use Botble\Support\Http\Requests\Request;

class ReviewSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'enabled_review' => new OnOffRule(),
        ];
    }
}
