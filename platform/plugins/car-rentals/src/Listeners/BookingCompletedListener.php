<?php

namespace Botble\CarRentals\Listeners;

use Botble\CarRentals\Enums\BookingStatusEnum;
use Botble\CarRentals\Enums\RevenueTypeEnum;
use Botble\CarRentals\Events\BookingStatusChanged;
use Botble\CarRentals\Facades\CarRentalsHelper;
use Botble\CarRentals\Models\Customer;
use Botble\CarRentals\Models\Revenue;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingCompletedListener
{
    public function handle(BookingStatusChanged $event): void
    {
        if ($event->booking->status != BookingStatusEnum::COMPLETED) {
            return;
        }

        $booking = $event->booking;
        $booking->loadMissing(['car', 'car.car']);

        if (! $booking->vendor_id) {
            return;
        }

        $vendor = Customer::query()->find($booking->vendor_id);

        if (! $vendor) {
            return;
        }

        try {
            $car = $booking->car->car;

            // Calculate commission fee
            $bookingAmountWithoutTax = $booking->amount - $booking->tax_amount;

            if (! CarRentalsHelper::isCommissionCategoryFeeBasedEnabled()) {
                $commissionFee = CarRentalsHelper::calculateCommissionFee($bookingAmountWithoutTax);
            } else {
                $commissionFee = CarRentalsHelper::calculateCarCommissionFee($bookingAmountWithoutTax, $car->id);
            }

            // Calculate vendor earnings
            $vendorEarnings = $bookingAmountWithoutTax - $commissionFee;

            DB::beginTransaction();

            // Create revenue record
            Revenue::query()->create([
                'customer_id' => $vendor->id,
                'booking_id' => $booking->id,
                'sub_amount' => $bookingAmountWithoutTax,
                'fee' => $commissionFee,
                'amount' => $vendorEarnings,
                'current_balance' => $vendor->balance + $vendorEarnings,
                'currency' => get_application_currency()->title,
                'description' => trans('plugins/car-rentals::revenue.forms.description_for_completed_booking', [
                    'booking' => $booking->booking_number,
                    'car' => $car->name,
                ]),
                'type' => RevenueTypeEnum::BOOKING_COMPLETED,
            ]);

            // Update vendor balance
            $vendor->balance += $vendorEarnings;
            $vendor->save();

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error('BookingCompletedListener: ' . $exception->getMessage());
        }
    }
}
