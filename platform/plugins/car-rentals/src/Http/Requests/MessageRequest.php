<?php

namespace Botble\CarRentals\Http\Requests;

use Botble\CarRentals\Enums\MessageStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class MessageRequest extends Request
{
    public function rules(): array
    {
        return [
            'status' => Rule::in(MessageStatusEnum::values()),
        ];
    }
}
