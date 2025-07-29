<?php

namespace Botble\CarRentals\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Rules\OnOffRule;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CarCategoryRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120', 'min:1'],
            'description' => ['nullable', 'string', 'max:400'],
            'is_default' => $onOffRule = new OnOffRule(),
            'is_featured' => $onOffRule,
            'parent_id' => ['nullable'],
            'order' => ['nullable', 'integer', 'min:0', 'max:10000'],
            'status' => ['required', 'string', Rule::in(BaseStatusEnum::values())],
        ];
    }
}
