<?php

namespace Botble\CarRentals\Http\Controllers\Vendor;

use Botble\Base\Facades\Assets;
use Botble\Base\Http\Controllers\BaseController;
use Botble\CarRentals\Facades\CarRentalsHelper;
use Botble\CarRentals\Models\CarReview;
use Botble\CarRentals\Models\Customer;
use Botble\CarRentals\Tables\Vendor\ReviewTable;

class ReviewController extends BaseController
{
    public function index(ReviewTable $table)
    {
        $this->pageTitle(__('Reviews'));

        Assets::addStylesDirectly('vendor/core/plugins/car-rentals/css/review.css');

        $vendorId = auth('customer')->id();

        $table
            ->setVendorId($vendorId)
            ->setView('core/table::table');

        return $table->render(CarRentalsHelper::viewPath('vendor-dashboard.table.base'));
    }

    public function destroy(CarReview $review)
    {
        $vendorId = auth('customer')->id();
        $car = $review->car;

        if (! $car || $car->author_type !== Customer::class || $car->author_id !== $vendorId) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('You do not have permission to delete this review.'));
        }

        $review->delete();

        return $this
            ->httpResponse()
            ->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
