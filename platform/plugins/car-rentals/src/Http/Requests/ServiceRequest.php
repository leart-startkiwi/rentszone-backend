<?php

namespace Botble\CarRentals\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\CarRentals\Enums\ServicePriceTypeEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class ServiceRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'status' => ['required', 'string', Rule::in(BaseStatusEnum::values())],
            'price' => ['required', 'numeric', 'min:0'],
            'price_type' => Rule::in(ServicePriceTypeEnum::values()),
            'description' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'logo' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
        ];
    }
}
