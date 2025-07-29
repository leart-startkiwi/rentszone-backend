<?php

namespace Database\Seeders\Themes\Main;

use Botble\Base\Supports\BaseSeeder;
use Botble\CarRentals\Enums\BookingStatusEnum;
use Botble\CarRentals\Facades\InvoiceHelper;
use Botble\CarRentals\Models\Booking;
use Botble\CarRentals\Models\BookingCar;
use Botble\CarRentals\Models\Car;
use Botble\CarRentals\Models\Customer;
use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingSeeder extends BaseSeeder
{
    public function run(): void
    {
        Booking::query()->truncate();
        BookingCar::query()->truncate();
        DB::table('cr_booking_service')->truncate();
        DB::table('cr_invoice_items')->truncate();
        DB::table('cr_invoices')->truncate();

        Payment::query()->truncate();

        $faker = $this->fake();
        $customers = Customer::query()->get();
        $currencyId = get_application_currency()->getKey();
        $cars = Car::query()->get();
        $vendorIds = Customer::query()->where('is_vendor', true)->pluck('id');

        $total = 20;

        for ($i = 0; $i < $total; $i++) {
            $customer = $customers->random();
            $car = $cars->random();

            $time = Carbon::now()->subMinutes(($total - $i) * 120 * rand(1, 10));

            $bookingCar = new BookingCar([
                'car_id' => $car->id,
                'car_name' => $car->name,
                'price' => $car->rental_rate,
                'currency_id' => $currencyId,
                'rental_start_date' => $time->toDateString(),
                'rental_end_date' => $time->clone()->addDays(rand(2, 4))->toDateString(),
            ]);

            /**
             * @var Booking $booking
             */
            $booking = Booking::query()->forceCreate([
                'customer_id' => $customer->id,
                'customer_name' => $customer->name,
                'customer_email' => $customer->email,
                'customer_phone' => $customer->customer_phone,
                'transaction_id' => strtoupper(Str::random(20)),
                'amount' => $bookingCar->price,
                'sub_total' => $bookingCar->price,
                'tax_amount' => 0,
                'currency_id' => $currencyId,
                'status' => $faker->randomElement(BookingStatusEnum::values()),
                'vendor_id' => $i % 3 == 0 ? $vendorIds->random() : null,
            ]);

            $bookingCar->booking()->associate($booking)->save();

            $booking->update([
                'booking_number' => Booking::generateUniqueBookingNumber(),
            ]);

            /**
             * @var Payment $payment
             */
            $payment = Payment::query()->create([
                'amount' => $booking->amount,
                'currency' => 'USD',
                'user_id' => $customer->id,
                'charge_id' => strtoupper(Str::random(12)),
                'payment_channel' => $faker->randomElement(PaymentMethodEnum::values()),
                'status' => $booking->status->getValue() === BookingStatusEnum::COMPLETED ? PaymentStatusEnum::COMPLETED : PaymentStatusEnum::PENDING,
                'order_id' => $booking->getKey(),
                'payment_type' => 'direct',
                'customer_id' => $customer->id,
                'customer_type' => $customer::class,
            ]);

            $booking->payment()->associate($payment)->save();

            InvoiceHelper::store($booking);
        }
    }
}
