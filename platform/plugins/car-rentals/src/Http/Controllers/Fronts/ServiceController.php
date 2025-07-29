<?php

namespace Botble\CarRentals\Http\Controllers\Fronts;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\CarRentals\Facades\BookingHelper;
use Botble\CarRentals\Models\Service;
use Closure;
use Illuminate\Http\Request;

class ServiceController extends BaseController
{
    public function __construct(protected BaseHttpResponse $response)
    {
        $this->middleware(function (Request $request, Closure $next) {
            abort_unless($request->ajax(), 404);

            return $next($request);
        });
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_ids' => ['nullable', 'array'],
        ]);

        $serviceIds = Service::query()
            ->wherePublished()
            ->whereIn('id', $request->input('service_ids', []))
            ->pluck('id')
            ->all();

        BookingHelper::saveCheckoutData([
            'service_ids' => $serviceIds,
        ]);

        return $this->response
            ->setMessage(__('Selected services successfully!'));
    }
}
