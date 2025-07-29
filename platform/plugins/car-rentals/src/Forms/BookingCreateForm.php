<?php

namespace Botble\CarRentals\Forms;

use Botble\Base\Facades\Assets;
use Botble\Base\Forms\FieldOptions\DatePickerFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\DatePickerField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\CarRentals\Enums\BookingStatusEnum;
use Botble\CarRentals\Http\Requests\CreateBookingRequest;
use Botble\CarRentals\Models\Booking;
use Botble\CarRentals\Models\Service;
use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Payment\Enums\PaymentStatusEnum;
use Carbon\Carbon;

class BookingCreateForm extends FormAbstract
{
    public function setup(): void
    {
        Assets::addScripts(['booking-create']);
        Assets::addScriptsDirectly('vendor/core/plugins/car-rentals/js/booking-car-search.js');
        Assets::addScriptsDirectly('vendor/core/plugins/car-rentals/js/customer-autocomplete.js');

        $today = Carbon::now();
        $tomorrow = Carbon::now()->addDay();
        $selectedStartDate = $today->format('Y-m-d');
        $selectedEndDate = $tomorrow->format('Y-m-d');

        $services = Service::query()
            ->select(['id', 'name', 'price', 'price_type'])
            ->where('status', 'published')
            ->get();

        $this
            ->model(Booking::class)
            ->setValidatorClass(CreateBookingRequest::class)
            ->withCustomFields()
            ->columns()
            ->add(
                'rental_start_date',
                DatePickerField::class,
                DatePickerFieldOption::make()
                ->label(trans('plugins/car-rentals::booking.start_date'))
                ->required()
                ->value($selectedStartDate)
                ->colspan(1)
            )
            ->add(
                'rental_end_date',
                DatePickerField::class,
                DatePickerFieldOption::make()
                ->label(trans('plugins/car-rentals::booking.end_date'))
                ->required()
                ->value($selectedEndDate)
                ->colspan(1)
            )
            ->add('search_cars', 'html', [
                'html' => view('plugins/car-rentals::bookings.partials.search-cars-button')->render(),
                'colspan' => 2,
            ])
            ->add('booking_form_container', 'html', [
                'html' => view('plugins/car-rentals::bookings.partials.car-selection-container')->render(),
                'colspan' => 2,
            ])
            ->add('car_id', 'hidden', [
                'value' => '',
                'attr' => [
                    'id' => 'car_id',
                ],
            ])
            ->add('booking_details_container', 'html', [
                'html' => view('plugins/car-rentals::bookings.partials.booking-details-header')->render(),
                'colspan' => 2,
            ])
            ->add('customer_search_container', 'html', [
                'html' => view('plugins/car-rentals::bookings.partials.customer-search')->render(),
                'colspan' => 2,
            ])
            ->add('customer_id', 'hidden', [
                'value' => '0',
                'attr' => [
                    'id' => 'customer_id',
                ],
            ])
            ->add('open_columns_wrapper', 'html', [
                'html' => view('plugins/car-rentals::bookings.partials.columns-wrapper')->render(),
            ])
            ->add(
                'name',
                TextField::class,
                TextFieldOption::make()
                ->label(trans('plugins/car-rentals::booking.customer_name'))
                ->required()
                ->colspan(1)
            )
            ->add(
                'email',
                TextField::class,
                TextFieldOption::make()
                ->label(trans('plugins/car-rentals::booking.email'))
                ->required()
                ->colspan(1)
            )
            ->add(
                'phone',
                TextField::class,
                TextFieldOption::make()
                ->label(trans('plugins/car-rentals::booking.phone'))
                ->required()
                ->colspan(1)
            )
            ->add(
                'customer_age',
                TextField::class,
                TextFieldOption::make()
                ->label(trans('plugins/car-rentals::booking.customer_age'))
                ->placeholder(trans('plugins/car-rentals::booking.customer_age'))
                ->colspan(1)
            )
            ->add(
                'address',
                TextField::class,
                TextFieldOption::make()
                ->label(trans('plugins/car-rentals::booking.address'))
                ->placeholder(trans('plugins/car-rentals::booking.address'))
                ->colspan(1)
            )
            ->add(
                'city',
                TextField::class,
                TextFieldOption::make()
                ->label(trans('plugins/car-rentals::booking.city'))
                ->placeholder(trans('plugins/car-rentals::booking.city'))
                ->colspan(1)
            )
            ->add(
                'state',
                TextField::class,
                TextFieldOption::make()
                ->label(trans('plugins/car-rentals::booking.state'))
                ->placeholder(trans('plugins/car-rentals::booking.state'))
                ->colspan(1)
            )
            ->add(
                'country',
                TextField::class,
                TextFieldOption::make()
                ->label(trans('plugins/car-rentals::booking.country'))
                ->placeholder(trans('plugins/car-rentals::booking.country'))
                ->colspan(1)
            )
            ->add(
                'zip',
                TextField::class,
                TextFieldOption::make()
                ->label(trans('plugins/car-rentals::booking.zip'))
                ->placeholder(trans('plugins/car-rentals::booking.zip'))
                ->colspan(1)
            )
            ->add(
                'note',
                TextareaField::class,
                TextareaFieldOption::make()
                ->label(trans('plugins/car-rentals::booking.note'))
                ->rows(3)
                ->placeholder(trans('plugins/car-rentals::booking.note_placeholder'))
                ->colspan(2)
            )
            ->add('booking_details_end', 'html', [
                'html' => view('plugins/car-rentals::bookings.partials.columns-wrapper-end')->render(),
            ]);

        if ($services->count()) {
            $servicesArray = $services->mapWithKeys(function ($service) {
                return [$service->id => $service];
            })->toArray();

            $this->addMetaBoxes([
                'services_box' => [
                    'title' => trans('plugins/car-rentals::booking.services'),
                    'content' => view('plugins/car-rentals::booking-services', [
                        'services' => $servicesArray,
                        'selectedServices' => [],
                    ])->render(),
                ],
            ]);
        }

        $this
            ->add(
                'payment_method',
                SelectField::class,
                SelectFieldOption::make()
                ->label(trans('plugins/car-rentals::booking.payment_method'))
                ->choices(PaymentMethodEnum::labels())
                ->helperText(trans('plugins/car-rentals::booking.payment_method_helper'))
                ->colspan(1)
            )
            ->add(
                'payment_status',
                SelectField::class,
                SelectFieldOption::make()
                ->label(trans('plugins/car-rentals::booking.payment_status'))
                ->choices(PaymentStatusEnum::labels())
                ->helperText(trans('plugins/car-rentals::booking.payment_status_helper'))
                ->colspan(1)
            )
            ->add(
                'transaction_id',
                TextField::class,
                TextFieldOption::make()
                ->label(trans('plugins/car-rentals::booking.transaction_id'))
                ->helperText(trans('plugins/car-rentals::booking.transaction_id_helper'))
                ->placeholder(trans('plugins/car-rentals::booking.transaction_id'))
                ->colspan(1)
            )
            ->add(
                'status',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('core/base::tables.status'))
                    ->choices(BookingStatusEnum::labels())
                    ->selected(BookingStatusEnum::PENDING)
            )
            ->setBreakFieldPoint('status');
    }
}
