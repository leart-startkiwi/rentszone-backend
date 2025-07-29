<?php

namespace Botble\CarRentals\Http\Middleware;

use Botble\Base\Facades\BaseHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotVendor
{
    public function handle(Request $request, Closure $next, string $guard = 'customer')
    {
        if (! Auth::guard($guard)->check() || ! Auth::guard($guard)->user()->is_vendor) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            }

            return redirect()->guest(route('customer.login'));
        }

        if (get_car_rentals_setting('verify_vendor', false) && ! Auth::guard($guard)->user()->vendor_verified_at) {
            if ($request->ajax() || $request->wantsJson()) {
                return response(__('Vendor account is not verified.'), 403);
            }

            return redirect()->guest(BaseHelper::getHomepageUrl());
        }

        return $next($request);
    }
}
