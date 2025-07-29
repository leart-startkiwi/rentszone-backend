<?php

namespace Botble\CarRentals\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CarReviewRequest extends Request
{
    public function rules(): array
    {
        return [
            'status' => ['required', 'string', Rule::in(BaseStatusEnum::values())],
        ];
    }
}
