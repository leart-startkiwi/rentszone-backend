<?php

namespace Botble\CarRentals\Http\Controllers\Vendor;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\CarRentals\Forms\Vendor\BookingForm;
use Botble\CarRentals\Http\Requests\UpdateBookingRequest;
use Botble\CarRentals\Models\Booking;
use Botble\CarRentals\Tables\Vendor\BookingTable;

class BookingController extends BaseController
{
    public function index(BookingTable $table)
    {
        $this->pageTitle(trans('plugins/car-rentals::booking.name'));

        return $table->renderTable();
    }

    public function edit(Booking $booking)
    {
        if ($booking->vendor_id != auth('customer')->id()) {
            abort(403);
        }

        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $booking->car->car_name]));

        return BookingForm::createFromModel($booking)->renderForm();
    }

    public function update(Booking $booking, UpdateBookingRequest $request)
    {
        if ($booking->vendor_id != auth('customer')->id()) {
            abort(403);
        }

        BookingForm::createFromModel($booking)
            ->setRequest($request)
            ->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('car-rentals.bookings.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(Booking $booking): DeleteResourceAction
    {
        if ($booking->vendor_id != auth('customer')->id()) {
            abort(403);
        }

        return DeleteResourceAction::make($booking);
    }
}
