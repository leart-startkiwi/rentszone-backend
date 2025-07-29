<?php

namespace Botble\CarRentals\Http\Requests\Fronts\Auth;

use Botble\Support\Http\Requests\Request;

class ChangePasswordRequest extends Request
{
    public function rules(): array
    {
        return [
            'old_password' => ['required', 'string', 'current_password:customer'],
            'password' => ['required', 'string', 'min:6', 'max:60', 'confirmed'],
        ];
    }
}
