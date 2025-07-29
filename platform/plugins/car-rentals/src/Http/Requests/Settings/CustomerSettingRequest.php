<?php

namespace Botble\CarRentals\Http\Requests\Settings;

use Botble\Base\Rules\MediaImageRule;
use Botble\Base\Rules\OnOffRule;
use Botble\Support\Http\Requests\Request;

class CustomerSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'verify_customer_email' => $onOffRule = new OnOffRule(),
            'enabled_customer_registration' => $onOffRule,
            'show_terms_and_policy_acceptance_checkbox' => $onOffRule,
            'max_filesize_upload_by_vendor' => $intRule = ['required', 'int', 'min:1'],
            'max_post_images_upload_by_vendor' => $intRule,
            'car_rentals_customer_default_avatar' => ['nullable', new MediaImageRule()],
        ];
    }
}
