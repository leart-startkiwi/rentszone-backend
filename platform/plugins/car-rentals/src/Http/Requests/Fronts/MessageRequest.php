<?php

namespace Botble\CarRentals\Http\Requests\Fronts;

use Botble\Base\Rules\EmailRule;
use Botble\Support\Http\Requests\Request;

class MessageRequest extends Request
{
    public function rules(): array
    {
        $rules = [
            'content' => ['required', 'string', 'max:1000'],
        ];

        if (! auth('customer')->check()) {
            $rules += [
                'name' => ['required', 'string', 'max:120'],
                'email' => ['required', new EmailRule()],
            ];
        }

        return $rules;
    }
}
