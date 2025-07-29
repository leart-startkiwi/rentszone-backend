<?php

namespace Botble\CarRentals\Http\Controllers;

use Botble\Base\Facades\Assets;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\CarRentals\Http\Requests\CouponRequest;
use Botble\CarRentals\Models\Coupon;
use Botble\CarRentals\Tables\CouponTable;
use Botble\JsValidation\Facades\JsValidator;
use Illuminate\Support\Str;

class CouponController extends BaseController
{
    public function __construct()
    {
        $this
            ->breadcrumb()
            ->add(trans('plugins/car-rentals::car-rentals.name'))
            ->add(trans('plugins/car-rentals::coupon.name'), route('car-rentals.coupons.index'));
    }

    public function index(CouponTable $table)
    {
        $this->pageTitle(trans('plugins/car-rentals::car-rentals.coupon.name'));

        Assets::addStylesDirectly('vendor/core/plugins/car-rentals/css/car-rentals.css');

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/car-rentals::car-rentals.coupon.create'));

        Assets::usingVueJS()
            ->addStylesDirectly('vendor/core/plugins/car-rentals/css/car-rentals.css')
            ->addScriptsDirectly('vendor/core/plugins/car-rentals/js/coupon.js')
            ->addScripts(['timepicker', 'input-mask', 'form-validation'])
            ->addStyles('timepicker');

        $jsValidation = JsValidator::formRequest(CouponRequest::class);

        return view('plugins/car-rentals::coupons.create', compact('jsValidation'));
    }

    public function store(CouponRequest $request)
    {
        $expiresAt = null;
        if ($request->boolean('is_unlimited_expires') === false) {
            $expiresAt = $request->date('end_date', 'Y-m-d')
                ->copy()
                ->setTimeFromTimeString($request->input('end_time'));
        }

        Coupon::query()->create([
            ...$request->all(),
            'expires_at' => $expiresAt,
            'is_unlimited' => $request->boolean('is_unlimited'),
            'is_unlimited_expires' => $request->boolean('is_unlimited_expires'),
        ]);

        return $this
            ->httpResponse()
            ->setNextUrl(route('car-rentals.coupons.index'))
            ->setPreviousUrl(route('car-rentals.coupons.index'))
            ->withCreatedSuccessMessage();
    }

    public function edit(Coupon $coupon)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $coupon->code]));

        Assets::usingVueJS()
            ->addStylesDirectly('vendor/core/plugins/car-rentals/css/car-rentals.css')
            ->addScriptsDirectly('vendor/core/plugins/car-rentals/js/coupon.js')
            ->addScripts(['timepicker', 'input-mask', 'form-validation'])
            ->addStyles('timepicker');

        return view('plugins/car-rentals::coupons.edit', compact('coupon'));
    }

    public function update(Coupon $coupon, CouponRequest $request)
    {
        $expiresAt = null;
        if ($request->boolean('is_unlimited_expires') === false) {
            $expiresAt = $request->date('end_date', 'Y-m-d')
                ->copy()
                ->setTimeFromTimeString($request->input('end_time'));
        }

        $coupon->update([
            ...$request->all(),
            'expires_at' => $expiresAt,
            'is_unlimited' => $request->boolean('is_unlimited'),
            'is_unlimited_expires' => $request->boolean('is_unlimited_expires'),
        ]);

        return $this
            ->httpResponse()
            ->setNextUrl(route('car-rentals.coupons.edit', $coupon))
            ->setPreviousUrl(route('car-rentals.coupons.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(Coupon $coupon)
    {
        return DeleteResourceAction::make($coupon);
    }

    public function postGenerateCoupon()
    {
        do {
            $code = strtoupper(Str::random(12));
        } while (Coupon::query()->where('code', $code)->exists());

        return $this
            ->httpResponse()
            ->setData($code);
    }
}
