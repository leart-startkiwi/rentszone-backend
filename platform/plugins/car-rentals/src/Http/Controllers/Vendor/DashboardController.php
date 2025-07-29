<?php

namespace Botble\CarRentals\Http\Controllers\Vendor;

use Botble\Base\Http\Controllers\BaseController;
use Botble\CarRentals\Enums\RevenueTypeEnum;
use Botble\CarRentals\Facades\CarRentalsHelper;
use Botble\CarRentals\Models\Booking;
use Botble\CarRentals\Models\Car;
use Botble\CarRentals\Models\CarReview;
use Botble\CarRentals\Models\Customer;
use Botble\CarRentals\Models\Message;
use Botble\CarRentals\Models\Revenue;
use Botble\CarRentals\Models\Withdrawal;
use Botble\Media\Chunks\Exceptions\UploadMissingFileException;
use Botble\Media\Chunks\Handler\DropZoneUploadHandler;
use Botble\Media\Chunks\Receiver\FileReceiver;
use Botble\Media\Facades\RvMedia;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class DashboardController extends BaseController
{
    public function index(Request $request)
    {
        $this->pageTitle(__('Dashboard'));

        $vendorId = auth('customer')->id();

        $totalCars = Car::query()->where('author_type', Customer::class)->where('author_id', $vendorId)->count();
        $totalBookings = Booking::query()->where('vendor_id', $vendorId)->count();
        $totalMessages = Message::query()->where('vendor_id', $vendorId)->count();

        [$startDate, $endDate] = CarRentalsHelper::getDateRangeInReport($request);
        $predefinedRange = $request->input('date_range', trans('plugins/car-rentals::reports.ranges.last_30_days'));

        // Recent bookings
        $bookings = Booking::query()
            ->where('vendor_id', $vendorId)
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->latest()
            ->with(['car', 'car.car'])
            ->limit(10)
            ->get();

        // Revenue data
        $revenue = Revenue::query()
            ->selectRaw(
                'SUM(CASE WHEN type IS NULL OR type = ? THEN sub_amount WHEN type = ? THEN sub_amount * -1 ELSE 0 END) as sub_amount,
                SUM(CASE WHEN type IS NULL OR type = ? THEN amount WHEN type = ? THEN amount * -1 ELSE 0 END) as amount,
                SUM(fee) as fee',
                [RevenueTypeEnum::ADD_AMOUNT, RevenueTypeEnum::SUBTRACT_AMOUNT, RevenueTypeEnum::ADD_AMOUNT, RevenueTypeEnum::SUBTRACT_AMOUNT]
            )
            ->where('customer_id', $vendorId)
            ->where(function ($query) use ($startDate, $endDate): void {
                $query->whereDate('created_at', '>=', $startDate)
                    ->whereDate('created_at', '<=', $endDate);
            })
            ->groupBy('customer_id')
            ->first();

        $withdrawal = Withdrawal::query()
            ->selectRaw('SUM(amount) as amount, SUM(fee) as fee')
            ->where('customer_id', $vendorId)
            ->whereIn('status', ['completed', 'pending', 'processing'])
            ->where(function ($query) use ($startDate, $endDate): void {
                $query->whereDate('created_at', '>=', $startDate)
                    ->whereDate('created_at', '<=', $endDate);
            })
            ->groupBy('customer_id')
            ->first();

        // Top performing cars
        $topCars = Car::query()
            ->where('author_type', Customer::class)
            ->where('author_id', $vendorId)
            ->select('cr_cars.*')
            ->selectRaw('(
                SELECT COUNT(*)
                FROM cr_bookings
                INNER JOIN cr_booking_cars ON cr_booking_cars.booking_id = cr_bookings.id
                WHERE cr_cars.id = cr_booking_cars.car_id
                AND cr_bookings.created_at >= ?
                AND cr_bookings.created_at <= ?
            ) as bookings_count', [$startDate->toDateTimeString(), $endDate->toDateTimeString()])
            ->selectRaw('(
                SELECT COALESCE(SUM(cr_bookings.amount), 0)
                FROM cr_bookings
                INNER JOIN cr_booking_cars ON cr_booking_cars.booking_id = cr_bookings.id
                WHERE cr_cars.id = cr_booking_cars.car_id
                AND cr_bookings.created_at >= ?
                AND cr_bookings.created_at <= ?
            ) as revenue', [$startDate->toDateTimeString(), $endDate->toDateTimeString()])
            ->orderByDesc('bookings_count')
            ->limit(5)
            ->get();

        // Recent reviews
        $recentReviews = CarReview::query()
            ->whereHas('car', function ($query) use ($vendorId) {
                $query->where('author_type', Customer::class)
                    ->where('author_id', $vendorId);
            })
            ->with(['car', 'customer'])
            ->latest()
            ->limit(5)
            ->get();

        // Maintenance alerts - simulated data
        $maintenanceAlerts = collect();

        // Get all cars with booking counts
        $carsWithBookings = Car::query()
            ->where('author_type', Customer::class)
            ->where('author_id', $vendorId)
            ->select('cr_cars.*')
            ->selectRaw('(
                SELECT COUNT(*)
                FROM cr_bookings
                INNER JOIN cr_booking_cars ON cr_booking_cars.booking_id = cr_bookings.id
                WHERE cr_cars.id = cr_booking_cars.car_id
            ) as bookings_count')
            ->get();

        // Set thresholds based on the actual data
        $maxBookings = $carsWithBookings->max('bookings_count') ?: 0;
        $highThreshold = max(2, (int) ($maxBookings * 0.7)); // 70% of max bookings
        $mediumThreshold = max(1, (int) ($maxBookings * 0.5)); // 50% of max bookings
        $lowThreshold = max(1, (int) ($maxBookings * 0.3)); // 30% of max bookings

        foreach ($carsWithBookings as $car) {
            // Only create alerts for cars with at least one booking
            if ($car->bookings_count > 0) {
                // Simulate maintenance alerts based on booking count
                if ($car->bookings_count >= $highThreshold) {
                    $maintenanceAlerts->push((object) [
                        'car' => $car,
                        'priority' => 'high',
                        'message' => __('This car has been booked frequently and may need maintenance.'),
                        'last_maintenance' => null,
                    ]);
                } elseif ($car->bookings_count >= $mediumThreshold) {
                    $maintenanceAlerts->push((object) [
                        'car' => $car,
                        'priority' => 'medium',
                        'message' => __('Consider scheduling maintenance for this car soon.'),
                        'last_maintenance' => null,
                    ]);
                } elseif ($car->bookings_count >= $lowThreshold) {
                    $maintenanceAlerts->push((object) [
                        'car' => $car,
                        'priority' => 'low',
                        'message' => __('This car may need a routine check-up.'),
                        'last_maintenance' => null,
                    ]);
                }
            }
        }

        $data = [
            'revenue' => [
                'amount' => $revenue?->amount ?: 0,
                'fee' => $revenue?->fee ?: 0,
                'sub_amount' => $revenue?->sub_amount ?: 0,
                'withdrawal' => $withdrawal?->amount ?: 0,
            ],
            'bookings' => $bookings,
            'predefinedRange' => $predefinedRange,
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
            'topCars' => $topCars,
            'recentReviews' => $recentReviews,
            'maintenanceAlerts' => $maintenanceAlerts,
        ];

        return CarRentalsHelper::view('vendor-dashboard.index', compact('totalCars', 'totalBookings', 'totalMessages', 'data'));
    }

    public function postUpload(Request $request)
    {
        $customer = auth('customer')->user();

        $uploadFolder = $customer->upload_folder;

        if (! RvMedia::isChunkUploadEnabled()) {
            $validator = Validator::make($request->all(), [
                'file.0' => ['required', 'image', 'mimes:jpg,jpeg,png'],
            ]);

            if ($validator->fails()) {
                return $this
                    ->httpResponse()
                    ->setError()
                    ->setMessage($validator->getMessageBag()->first());
            }

            $result = RvMedia::handleUpload(Arr::first($request->file('file')), 0, $uploadFolder);

            if ($result['error']) {
                return $this
                    ->httpResponse()
                    ->setError()
                    ->setMessage($result['message']);
            }

            return $this
                ->httpResponse()
                ->setData($result['data']);
        }

        try {
            // Create the file receiver
            $receiver = new FileReceiver('file', $request, DropZoneUploadHandler::class);
            // Check if the upload is success, throw exception or return response you need
            if ($receiver->isUploaded() === false) {
                throw new UploadMissingFileException();
            }
            // Receive the file
            $save = $receiver->receive();
            // Check if the upload has finished (in chunk mode it will send smaller files)
            if ($save->isFinished()) {
                $result = RvMedia::handleUpload($save->getFile(), 0, $uploadFolder);

                if (! $result['error']) {
                    return $this
                        ->httpResponse()
                        ->setData($result['data']);
                }

                return $this
                    ->httpResponse()
                    ->setError()
                    ->setMessage($result['message']);
            }
            // We are in chunk mode, lets send the current progress
            $handler = $save->handler();

            return response()->json([
                'done' => $handler->getPercentageDone(),
                'status' => true,
            ]);
        } catch (Exception $exception) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function postUploadFromEditor(Request $request)
    {
        $customer = auth('customer')->user();

        $uploadFolder = $customer->upload_folder;

        return RvMedia::uploadFromEditor($request, 0, $uploadFolder);
    }
}
