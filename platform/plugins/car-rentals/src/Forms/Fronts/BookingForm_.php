<?php

namespace Botble\CarRentals\Forms\Fronts;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Forms\FieldOptions\ButtonFieldOption;
use Botble\Base\Forms\FieldOptions\DatePickerFieldOption;
use Botble\Base\Forms\FieldOptions\HtmlFieldOption;
use Botble\Base\Forms\FieldOptions\MultiChecklistFieldOption;
use Botble\Base\Forms\Fields\DateField;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\MultiCheckListField;
use Botble\CarRentals\Facades\CarRentalsHelper;
use Botble\CarRentals\Forms\Fronts\Auth\FieldOptions\TextFieldOption;
use Botble\CarRentals\Http\Requests\Fronts\BookingRequest;
use Botble\CarRentals\Models\Booking;
use Botble\CarRentals\Models\Car;
use Botble\CarRentals\Models\Service;
use Botble\Theme\Facades\Theme;
use Botble\Theme\FormFront;
use Carbon\Carbon;

class BookingForm extends FormFront
{
    public function setup(): void
    {
        Theme::asset()->add('booking-css', 'vendor/core/plugins/car-rentals/css/front-booking-form.css', version: get_cms_version());
        Theme::asset()->container('footer')->add('booking-js', 'vendor/core/plugins/car-rentals/js/front-booking-form.js', version: get_cms_version());

        $carId = $this->model['car_id'] ?? null;

        if (! $carId) {
            return;
        }

        $car = Car::query()->whereKey($carId)->first();

        if (! $car) {
            return;
        }

        $dateFormat = CarRentalsHelper::getDateFormat();

        $startDate = BaseHelper::stringify(request()->query('rental_start_date', Carbon::now()->format($dateFormat)));
        $endDate = BaseHelper::stringify(request()->query('rental_end_date', Carbon::now()->addDay()->format($dateFormat)));

        $rentalPrice = $car->getCarRentalPrice($startDate, $endDate);

        // Calculate tax amount
        $taxAmount = $car->calculateTaxAmount($rentalPrice);
        $taxInfo = $car->getTaxInfo($taxAmount);
        $totalAmount = $rentalPrice + $taxAmount;

        $services = Service::query()->select(['id', 'name', 'price'])->wherePublished()->get();

        $serviceOptions = [];

        foreach ($services as $service) {
            $serviceOptions[$service->id] = $service->name . ' - ' . format_price($service->price);
        }

        $this
            ->contentOnly()
            ->setUrl(route('public.booking'))
            ->model(Booking::class)
            ->setValidatorClass(BookingRequest::class)
            ->setFormOption('class', 'booking-form')
            ->setFormOption('data-estimate-url', route('public.ajax.booking.estimate'))
            ->add(
                'car_id',
                'hidden',
                TextFieldOption::make()
                    ->value($carId)
            )
            ->add(
                'rental_start_date',
                DateField::class,
                DatePickerFieldOption::make()
                    ->label(__('Start Date'))
                    ->value($startDate)
                    ->required()
            )
            ->add(
                'rental_end_date',
                DateField::class,
                DatePickerFieldOption::make()
                    ->label(__('End Date'))
                    ->value($endDate)
                    ->required()
            )
            ->add(
                'service_ids[]',
                MultiCheckListField::class,
                MultiChecklistFieldOption::make()
                    ->label(__('Additional Services'))
                    ->choices($serviceOptions)
                    ->colspan(2)
            )
            ->add('border_wrapper_after', HtmlField::class, HtmlFieldOption::make()->content('<div class="border-wrapper-after"></div>')->colspan(2))
            ->add(
                'total_estimate',
                HtmlField::class,
                HtmlFieldOption::make()
                    ->view('plugins/car-rentals::cars.partials.booking-form-estimate', [
                        'total' => $totalAmount,
                        'subtotal' => $rentalPrice,
                        'tax' => $taxAmount,
                        'taxInfo' => $taxInfo,
                    ])
                    ->colspan(2)
            )
            ->add(
                'submit',
                'submit',
                ButtonFieldOption::make()
                    ->cssClass('btn btn-primary')
                    ->label(__('Book Now'))
            );
    }
}
