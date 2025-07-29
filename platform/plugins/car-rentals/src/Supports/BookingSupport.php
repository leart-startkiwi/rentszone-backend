<?php

namespace Botble\CarRentals\Supports;

use Illuminate\Support\Str;

class BookingSupport
{
    public function getCheckoutData(?string $key = null): mixed
    {
        $checkoutToken = session('checkout_token');

        if (! $checkoutToken) {
            $checkoutToken = Str::upper(Str::random(32));
        }

        $sessionData = [];
        if (session()->has($checkoutToken)) {
            $sessionData = session($checkoutToken);
        }

        if ($key) {
            return $sessionData[$key] ?? null;
        }

        return $sessionData;
    }

    public function saveCheckoutData(array $data): void
    {
        $checkoutToken = session('checkout_token');

        $sessionData = $this->getCheckoutData();

        $data = array_merge($sessionData, $data);

        session()->put($checkoutToken, $data);
    }
}
