<?php

namespace Botble\CarRentals\Http\Controllers\Fronts;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\CarRentals\Facades\BookingHelper;
use Botble\CarRentals\Services\CouponService;
use Closure;
use Illuminate\Http\Request;

class CouponController extends BaseController
{
    public function __construct(protected BaseHttpResponse $response)
    {
        $this->middleware(function (Request $request, Closure $next) {
            abort_unless($request->ajax(), 404);

            return $next($request);
        });
    }

    public function apply(Request $request, CouponService $couponService): BaseHttpResponse
    {
        $request->validate([
            'coupon_code' => ['required', 'string'],
        ]);

        $couponCode = $request->input('coupon_code');

        $coupon = $couponService->getCouponByCode($couponCode);

        if ($coupon === null) {
            return $this->response
                ->setError()
                ->setMessage(__('This coupon is invalid!'));
        }

        BookingHelper::saveCheckoutData([
            'coupon_code' => $couponCode,
        ]);

        return $this->response
            ->setMessage(__('Applied coupon ":code" successfully!', ['code' => $couponCode]));
    }

    public function remove(): BaseHttpResponse
    {
        $couponCode = BookingHelper::getCheckoutData('coupon_code');

        if (! $couponCode) {
            return $this->response
                ->setError()
                ->setMessage(__('This coupon is not used yet!'));
        }

        BookingHelper::saveCheckoutData([
            'coupon_code' => null,
            'coupon_amount' => 0,
        ]);

        return $this->response
            ->setMessage(__('Removed coupon :code successfully!', ['code' => $couponCode]));
    }

    public function refresh(): BaseHttpResponse
    {
        return $this->response
            ->setData(view('plugins/car-rentals::coupons.partials.form')->render());
    }
}
