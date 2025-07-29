<?php

namespace Botble\CarRentals\Http\Controllers\Fronts;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\CarRentals\Enums\CarStatusEnum;
use Botble\CarRentals\Enums\ModerationStatusEnum;
use Botble\CarRentals\Facades\BookingHelper;
use Botble\CarRentals\Facades\CarListHelper;
use Botble\CarRentals\Facades\CarRentalsHelper;
use Botble\CarRentals\Forms\Fronts\CheckoutForm;
use Botble\CarRentals\Http\Requests\Fronts\BookingRequest;
use Botble\CarRentals\Http\Requests\Fronts\CheckoutRequest;
use Botble\CarRentals\Http\Requests\Fronts\ReviewRequest;
use Botble\CarRentals\Http\Resources\LocationResource;
use Botble\CarRentals\Models\Booking;
use Botble\CarRentals\Models\BookingCar;
use Botble\CarRentals\Models\Car;
use Botble\CarRentals\Models\CarAddress;
use Botble\CarRentals\Models\CarMake;
use Botble\CarRentals\Models\CarReview;
use Botble\CarRentals\Models\Currency;
use Botble\CarRentals\Models\Customer;
use Botble\CarRentals\Models\Service;
use Botble\CarRentals\Repositories\Interfaces\CarInterface;
use Botble\CarRentals\Services\BookingService;
use Botble\CarRentals\Services\CouponService;
use Botble\Location\Repositories\Interfaces\CityInterface;
use Botble\Location\Repositories\Interfaces\CountryInterface;
use Botble\Location\Repositories\Interfaces\StateInterface;
use Botble\Optimize\Facades\OptimizerHelper;
use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Payment\Services\Gateways\BankTransferPaymentService;
use Botble\Payment\Services\Gateways\CodPaymentService;
use Botble\Payment\Supports\PaymentHelper;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\SeoHelper\SeoOpenGraph;
use Botble\Slug\Facades\SlugHelper;
use Botble\Theme\Facades\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class PublicController extends BaseController
{
    public function getCars(Request $request)
    {
        SeoHelper::setTitle(__('Cars'));

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Cars'), route('public.cars'));

        $request->validate([
            'pickup_location' => ['nullable', 'exists:states,id'],
            'drop_off_location' => ['nullable', 'exists:states,id'],
            'rental_start_date' => ['nullable', 'date'],
            'rental_end_date' => ['nullable', 'date'],
        ]);

        $pickupLocation = $request->input('pickup_location');
        $dropOffLocation = $request->input('drop_off_location');

        $startDate = $request->input('rental_start_date') ? CarRentalsHelper::dateFromRequest($request->input('rental_start_date')) : null;
        $endDate = $request->input('rental_end_date') ? CarRentalsHelper::dateFromRequest($request->input('rental_end_date')) : null;

        $query = Car::query()->active();

        if ($startDate && $endDate) {
            $query->whereAvailableAt([
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);
        }

        if ($pickupLocation) {
            $query->whereHas('pickupAddress', function ($query) use ($pickupLocation): void {
                $query->where('state_id', $pickupLocation);
            });
        }

        if ($dropOffLocation) {
            $query->whereHas('returnAddress', function ($query) use ($dropOffLocation): void {
                $query->where('state_id', $dropOffLocation);
            });
        }

        $cars = $query->paginate();

        return Theme::scope('car-rentals.cars', compact('cars'), 'plugins/car-rentals::themes.cars')->render();
    }

    public function getCar(string $slug)
    {
        $slug = SlugHelper::getSlug($slug, SlugHelper::getPrefix(Car::class));

        abort_unless($slug, 404);

        $version = get_cms_version();

        Theme::asset()
            ->add('front-car-rentals-css', 'vendor/core/plugins/car-rentals/css/front-theme.css', version: $version);

        $car = $slug->reference;

        abort_unless($car, 404);

        $car
            ->loadMissing(['tags', 'make', 'pickupAddress', 'returnAddress'])
            ->loadSum('reviews', 'star')
            ->loadCount('reviews');

        abort_if($car->status->getValue() !== CarStatusEnum::AVAILABLE, 404);

        $enabledPostApproval = CarRentalsHelper::isEnabledPostApproval();

        if ($enabledPostApproval) {
            abort_if($car->moderation_status->getValue() !== ModerationStatusEnum::APPROVED, 404);
        }

        $reviews = CarReview::query()
            ->with('customer')
            ->where('car_id', $car->getKey())
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->paginate();

        SeoHelper::setTitle($car->name)->setDescription(Str::words($car->description, 120));

        $meta = new SeoOpenGraph();

        $meta->setDescription($car->description);
        $meta->setUrl($car->url);
        $meta->setTitle($car->name);
        $meta->setType('article');

        SeoHelper::setSeoOpenGraph($meta);

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Cars'), route('public.cars'))
            ->add($car->name, $car->url);

        if (function_exists('admin_bar')) {
            admin_bar()->registerLink(__('Edit this car'), route('car-rentals.cars.edit', $car->getKey()));
        }

        return Theme::scope('car-rentals.car', compact('car', 'reviews'), 'plugins/car-rentals::themes.car')->render();
    }

    public function getService(string $slug)
    {
        $slug = SlugHelper::getSlug($slug, SlugHelper::getPrefix(Service::class));

        abort_unless($slug, 404);

        $service = $slug->reference;

        abort_if($service->status->getValue() !== BaseStatusEnum::PUBLISHED, 404);

        SeoHelper::setTitle($service->name)->setDescription(Str::words($service->description, 120));

        $meta = new SeoOpenGraph();

        $meta->setDescription($service->description);
        $meta->setUrl($service->url);
        $meta->setTitle($service->name);
        $meta->setType('article');

        SeoHelper::setSeoOpenGraph($meta);

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Services'))
            ->add($service->name, $service->url);

        if (function_exists('admin_bar')) {
            admin_bar()->registerLink(__('Edit this car'), route('car-rentals.services.edit', $service->getKey()));
        }

        return Theme::scope('car-rentals.service', compact('service'), 'plugins/car-rentals::themes.service')->render();
    }

    public function postBooking(BookingRequest $request)
    {
        Car::query()
            ->findOrFail($request->input('car_id'));

        $token = md5(Str::random(40));

        session([
            $token => $request->except(['_token']),
            'checkout_token' => $token,
        ]);

        return $this
            ->httpResponse()
            ->setNextUrl(route('public.booking.form', $token));
    }

    public function getBooking(string $token)
    {
        SeoHelper::setTitle(__('Booking'));

        OptimizerHelper::disable();

        $sessionData = [];
        if (session()->has($token)) {
            $sessionData = session($token);
        }

        $carId = Arr::get($sessionData, 'car_id');

        if (! $carId) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__(
                    'This car is not available for booking!',
                ))
                ->withInput();
        }

        $car = Car::query()
            ->with(['tax', 'returnAddress', 'pickupAddress'])
            ->whereKey($carId)
            ->first();

        if (! $car) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__(
                    'This car is not available for booking!',
                ))
                ->withInput();
        }

        $startDate = $sessionData['rental_start_date'] ? CarRentalsHelper::dateFromRequest($sessionData['rental_start_date']) : null;
        $endDate = $sessionData['rental_end_date'] ? CarRentalsHelper::dateFromRequest($sessionData['rental_end_date']) : null;

        if (! $car->isAvailableAt(['start_date' => $startDate, 'end_date' => $endDate])) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__(
                    'This car is not available for booking from :start_date to :end_date!',
                    ['start_date' => $startDate->toDateString(), 'end_date' => $endDate->toDateString()]
                ))
                ->withInput();
        }

        $rentalCarAmount = $car->getCarRentalPrice($startDate->toDateString(), $endDate->toDateString());

        $amount = $rentalCarAmount;

        $serviceAmount = 0;

        if ($serviceIds = Arr::get($sessionData, 'service_ids', [])) {
            $services = Service::query()->whereIn('id', $serviceIds)->get();

            $serviceAmount = $services->sum('price');
        }

        $totalBeforeTax = $amount + $serviceAmount;

        // Calculate tax amount using the Car model method
        $taxAmount = $car->calculateTaxAmount($totalBeforeTax);

        // Get tax information for display
        $taxTitle = $car->getTaxInfo($taxAmount);

        $totalAmount = $totalBeforeTax + $taxAmount;

        $data = [
            'car' => $car,
            'amount' => $amount + $serviceAmount,
            'totalAmount' => $totalAmount,
            'taxTitle' => $taxTitle,
            'taxAmount' => $taxAmount,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'couponCode' => Arr::get($sessionData, 'coupon_code'),
            'couponAmount' => Arr::get($sessionData, 'coupon_amount'),
            'token' => $token,
            'rentalCarAmount' => $rentalCarAmount,
            'serviceIds' => $serviceIds,
            'services' => $services ?? [],
        ];

        return view(
            'plugins/car-rentals::checkouts.index',
            [
                'checkoutForm' => CheckoutForm::createFromArray($data),
                ...$data,
            ],
        );
    }

    public function updateGetBooking(string $token)
    {
        $sessionData = [];
        if (session()->has($token)) {
            $sessionData = session($token);
        }

        $carId = Arr::get($sessionData, 'car_id');

        if (! $carId) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__(
                    'This car is not available for booking!',
                ))
                ->withInput();
        }

        $car = Car::query()
            ->with(['tax', 'returnAddress', 'pickupAddress'])
            ->whereKey($carId)
            ->first();

        if (! $car) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__(
                    'This car is not available for booking!',
                ))
                ->withInput();
        }

        $startDate = $sessionData['rental_start_date'] ? CarRentalsHelper::dateFromRequest($sessionData['rental_start_date']) : null;
        $endDate = $sessionData['rental_end_date'] ? CarRentalsHelper::dateFromRequest($sessionData['rental_end_date']) : null;

        if (! $car->isAvailableAt(['start_date' => $startDate, 'end_date' => $endDate])) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__(
                    'This car is not available for booking from :start_date to :end_date!',
                    ['start_date' => $startDate->toDateString(), 'end_date' => $endDate->toDateString()]
                ))
                ->withInput();
        }

        $rentalCarAmount = $car->getCarRentalPrice($startDate->toDateString(), $endDate->toDateString());

        $amount = $rentalCarAmount;

        $serviceAmount = 0;

        if ($serviceIds = Arr::get($sessionData, 'service_ids', [])) {
            $services = Service::query()->whereIn('id', $serviceIds)->get();

            $serviceAmount = $services->sum('price');
        }

        $discountAmount = 0;

        if ($couponCode = Arr::get($sessionData, 'coupon_code')) {
            $couponService = new CouponService();

            $coupon = $couponService->getCouponByCode($couponCode);

            if ($coupon !== null) {
                $discountAmount = $couponService->getDiscountAmount(
                    $coupon->type->getValue(),
                    $coupon->value,
                    $amount
                );

                BookingHelper::saveCheckoutData([
                    'coupon_amount' => $discountAmount,
                ]);
            }
        }

        $totalBeforeTax = $amount + $serviceAmount;

        // Calculate tax amount using the Car model method
        $taxAmount = $car->calculateTaxAmount($totalBeforeTax);

        // Get tax information for display
        $taxTitle = $car->getTaxInfo($taxAmount);

        $totalAmount = $totalBeforeTax + $taxAmount - $discountAmount;

        $data = [
            'car' => $car,
            'amount' => $amount + $serviceAmount,
            'totalAmount' => $totalAmount,
            'taxTitle' => $taxTitle,
            'taxAmount' => $taxAmount,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'couponCode' => Arr::get($sessionData, 'coupon_code'),
            'couponAmount' => $discountAmount,
            'token' => $token,
            'services' => $services ?? [],
            'rentalCarAmount' => $rentalCarAmount,
        ];

        return $this
            ->httpResponse()
            ->setData(view('plugins/car-rentals::checkouts.partials.booking-information', $data)->render());
    }

    public function postCheckout(CheckoutRequest $request)
    {
        $sessionData = BookingHelper::getCheckoutData();

        if (! $carId = Arr::get($sessionData, 'car_id')) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__(
                    'This car is not available for booking!',
                ))
                ->withInput();
        }

        $car = Car::query()
            ->with('tax')
            ->whereKey($carId)
            ->first();

        if (! $car) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__(
                    'This car is not available for booking!',
                ))
                ->withInput();
        }

        $startDate = $sessionData['rental_start_date'] ? CarRentalsHelper::dateFromRequest($sessionData['rental_start_date']) : null;
        $endDate = $sessionData['rental_end_date'] ? CarRentalsHelper::dateFromRequest($sessionData['rental_end_date']) : null;

        $serviceAmount = 0;
        $services = collect();

        if ($serviceIds = Arr::get($sessionData, 'service_ids')) {
            $services = Service::query()->whereIn('id', $serviceIds)->get();

            $serviceAmount = $services->sum('price');
        }

        if (! $car->isAvailableAt(['start_date' => $startDate, 'end_date' => $endDate])) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__(
                    'This car is not available for booking from :start_date to :end_date!',
                    ['start_date' => $startDate->toDateString(), 'end_date' => $endDate->toDateString()]
                ))
                ->withInput();
        }

        if ($request->input('is_register') == 1) {
            $request->validate([
                'customer_email' => 'required|max:60|min:6|email|unique:cr_customers,email',
            ]);

            $customer = Customer::query()->create([
                'name' => BaseHelper::clean($request->input('customer_name')),
                'email' => BaseHelper::clean($request->input('customer_email')),
                'phone' => BaseHelper::clean($request->input('customer_phone')),
                'password' => Hash::make($request->input('password')),
            ]);

            Auth::guard('customer')->loginUsingId($customer->getKey());
        }

        $discountAmount = 0;

        $rentalCarAmount = $car->getCarRentalPrice($startDate->toDateString(), $endDate->toDateString());

        $amount = $rentalCarAmount + $serviceAmount;

        // Calculate tax amount using the Car model method
        $taxAmount = $car->calculateTaxAmount($amount);

        if ($couponCode = Arr::get($sessionData, 'coupon_code')) {
            $couponService = new CouponService();

            $coupon = $couponService->getCouponByCode($couponCode);

            if ($coupon !== null) {
                $discountAmount = $couponService->getDiscountAmount(
                    $coupon->type->getValue(),
                    $coupon->value,
                    $amount
                );

                BookingHelper::saveCheckoutData([
                    'coupon_amount' => $discountAmount,
                ]);
            }
        }

        $totalAmount = ($amount + $taxAmount) - $discountAmount;

        $booking = new Booking($request->validated());

        $booking->sub_total = $amount;
        $booking->coupon_code = $couponCode;
        $booking->coupon_amount = $discountAmount;
        $booking->amount = $totalAmount;
        $booking->tax_amount = $taxAmount;
        $booking->currency_id = $request->input('currency_id', strtoupper(get_application_currency()->id));
        $booking->booking_number = Booking::generateUniqueBookingNumber();
        $booking->transaction_id = Str::upper(Str::random(32));
        $booking->vendor_id = $car->author_type == Customer::class && $car->author->is_vendor ? $car->author_id : null;

        if (Auth::guard('customer')->check()) {
            $booking->customer_id = Auth::guard('customer')->id();
        }

        $booking->save();

        session()->put('booking_transaction_id', $booking->transaction_id);

        BookingCar::query()->create([
            'booking_id' => $booking->id,
            'car_id' => $car->id,
            'car_image' => $car->image,
            'car_name' => $car->name,
            'rental_start_date' => $startDate,
            'rental_end_date' => $endDate,
            'price' => $rentalCarAmount,
            'pickup_address_id' => $car->pick_address_id,
            'return_address_id' => $car->return_address_id,
            'currency_id' => $request->input('currency_id', strtoupper(get_application_currency()->id)),
        ]);

        $booking->services()->attach($services->pluck('id')->all());

        $request->merge([
            'order_id' => $booking->getKey(),
        ]);

        $data = [
            'error' => false,
            'message' => false,
            'amount' => $booking->amount,
            'currency' => strtoupper(get_application_currency()->title),
            'type' => $request->input('payment_method'),
            'charge_id' => null,
        ];

        if (is_plugin_active('payment')) {
            session()->put('selected_payment_method', $data['type']);

            $paymentData = apply_filters(PAYMENT_FILTER_PAYMENT_DATA, [], $request);

            switch ($request->input('payment_method')) {
                case PaymentMethodEnum::COD:
                    $codPaymentService = app(CodPaymentService::class);
                    $data['charge_id'] = $codPaymentService->execute($paymentData);
                    $data['message'] = trans('plugins/payment::payment.payment_pending');

                    break;

                case PaymentMethodEnum::BANK_TRANSFER:
                    $bankTransferPaymentService = app(BankTransferPaymentService::class);
                    $data['charge_id'] = $bankTransferPaymentService->execute($paymentData);
                    $data['message'] = trans('plugins/payment::payment.payment_pending');

                    break;

                default:
                    $data = apply_filters(PAYMENT_FILTER_AFTER_POST_CHECKOUT, $data, $request);

                    break;
            }

            if ($checkoutUrl = Arr::get($data, 'checkoutUrl')) {
                return $this
                    ->httpResponse()
                    ->setError($data['error'])
                    ->setNextUrl($checkoutUrl)
                    ->setData(['checkoutUrl' => $checkoutUrl])
                    ->withInput()
                    ->setMessage($data['message']);
            }

            if ($data['error'] || ! $data['charge_id']) {
                return $this
                    ->httpResponse()
                    ->setError()
                    ->setNextUrl(PaymentHelper::getCancelURL())
                    ->withInput()
                    ->setMessage($data['message'] ?: __('Checkout error!'));
            }

            $bookingService = new BookingService();

            $bookingService->processBooking($booking->getKey(), $data['charge_id']);

            $redirectUrl = PaymentHelper::getRedirectURL();
        } else {
            $redirectUrl = route('public.booking.information', $booking->transaction_id);
        }

        if ($token = $request->input('token')) {
            session()->forget($token);
            session()->forget('checkout_token');
        }

        return $this
            ->httpResponse()
            ->setNextUrl($redirectUrl)
            ->setMessage(__('Booking successfully!'));
    }

    public function getCheckoutSuccess(string $transactionId)
    {
        $booking = Booking::query()
            ->where('transaction_id', $transactionId)
            ->orderByDesc('id')
            ->first();

        abort_unless($booking, 404);

        if (is_plugin_active('payment') && (float) $booking->amount && ! $booking->payment_id) {
            return $this
                ->httpResponse()
                ->setError()
                ->setNextUrl(PaymentHelper::getCancelURL())
                ->setMessage(__('Payment failed!'));
        }

        return view('plugins/car-rentals::checkouts.thank-you', compact('booking'));
    }

    public function estimateBooking(Request $request)
    {
        $request->validate([
            'car_id' => ['required', 'exists:cr_cars,id'],
            'rental_start_date' => ['required', 'date'],
            'rental_end_date' => ['required', 'date'],
            'service_ids' => ['nullable', 'array'],
        ]);

        $car = Car::query()
            ->whereKey($request->input('car_id'))
            ->first();

        $startDate = $request->input('rental_start_date') ? CarRentalsHelper::dateFromRequest($request->input('rental_start_date')) : null;
        $endDate = $request->input('rental_end_date') ? CarRentalsHelper::dateFromRequest($request->input('rental_end_date')) : null;

        $rentalCarAmount = $car->getCarRentalPrice($startDate->toDateString(), $endDate->toDateString());

        $amount = $rentalCarAmount;

        $serviceAmount = 0;

        if ($serviceIds = $request->input('service_ids', [])) {
            $services = Service::query()->whereIn('id', $serviceIds)->get();

            $serviceAmount = $services->sum('price');
        }

        $totalBeforeTax = $amount + $serviceAmount;

        // Calculate tax amount using the Car model method
        $taxAmount = $car->calculateTaxAmount($totalBeforeTax);

        // Get tax information for display
        $taxInfo = $car->getTaxInfo($taxAmount);

        $totalAmount = $totalBeforeTax + $taxAmount;

        $data = [
            'subtotal' => $amount + $serviceAmount,
            'total' => $totalAmount,
            'tax' => $taxAmount,
            'taxInfo' => $taxInfo,
            'discount' => 0,
        ];

        return $this
            ->httpResponse()
            ->setData(view('plugins/car-rentals::cars.partials.booking-form-estimate', [...$data])->render());
    }

    public function postCarReviews(ReviewRequest $request)
    {
        abort_unless(CarRentalsHelper::isEnabledCarReviews(), 404);

        $customer = Auth::guard('customer')->user();

        if (! $customer) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('Please login to add review!'));
        }

        $car = Car::query()
            ->whereKey($request->input('car_id'))
            ->first();

        if (! $car) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('Car not found!'));
        }

        $isExistReview = CarReview::query()
            ->where('car_id', $car->getKey())
            ->where('customer_id', $customer->getKey())
            ->exists();

        if ($isExistReview) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('You have already reviewed this car!'));
        }

        CarReview::query()->create([
            ...$request->validated(),
            'status' => BaseStatusEnum::PUBLISHED,
        ]);

        return $this
            ->httpResponse()
            ->setMessage(__('Added review successfully!'));
    }

    public function ajaxGetCars(Request $request)
    {
        $requestQuery = CarListHelper::getCarFilters($request->input());

        $with = [
            'slugable',
            'transmission',
            'fuel',
            'pickupAddress',
            'make',
        ];

        $sortBy = $requestQuery['sort_by'] ?? 'recently_added';

        $perPage = $requestQuery['per_page'] ?? Arr::first(CarListHelper::getPerPageParams());

        $currentPage = $requestQuery['page'] ?? 1;

        $cars = app(CarInterface::class)->getCars(
            $requestQuery,
            [
                'with' => $with,
                'order_by' => $sortBy,
                'paginate' => [
                    'per_page' => $perPage ?: 10,
                    'current_paged' => $currentPage ?: 1,
                ],
            ],
        );

        $additional['total'] = $cars->total();

        if ($additional['total']) {
            $message = __(':total items found', [
                'total' => $cars->total(),
            ]);
        } else {
            $message = __('No results found');
        }

        $additional['message'] = $message;

        $carsView = Theme::getThemeNamespace('views.car-list.partials.car-items');

        if (! view()->exists($carsView)) {
            $carsView = 'plugins/car-rentals::themes.includes.car-items';
        }

        $filtersData['cars'] = $cars;

        $filtersView = Theme::getThemeNamespace('views.car-list.partials.filters');

        if (view()->exists($filtersView)) {
            $additional['filters_html'] = view(
                $filtersView,
                $filtersData
            )->render();
        }

        return $this
            ->httpResponse()
            ->setData(view($carsView, compact('cars'))->render())
            ->setAdditional($additional)
            ->setMessage($message);
    }

    public function switchCurrency(string $title, Request $request)
    {
        if (empty($title)) {
            $title = $request->input('currency');
        }

        if (! $title) {
            return $this->httpResponse();
        }

        $currency = Currency::query()->where('title', $title)->first();

        if ($currency) {
            cms_currency()->setApplicationCurrency($currency);
        }

        $url = URL::previous();

        if (! $url || $url === URL::current()) {
            return $this
                ->httpResponse()
                ->setNextUrl(BaseHelper::getHomepageUrl());
        }

        if (Str::contains($url, ['min_price', 'max_price'])) {
            $url = preg_replace('/&min_price=[0-9]+/', '', $url);
            $url = preg_replace('/&max_price=[0-9]+/', '', $url);
        }

        return $this
            ->httpResponse()
            ->setNextUrl($url);
    }

    public function ajaxGetLocation(
        Request $request,
        BaseHttpResponse $response
    ) {
        if (! is_plugin_active('location')) {
            return $response->setData([[], 'total' => 0]);
        }

        $cityRepository = app()->make(CityInterface::class);
        $stateRepository = app()->make(StateInterface::class);
        $countryRepository = app()->make(CountryInterface::class);

        $request->validate([
            'k' => ['nullable', 'string'],
            'type' => ['required', 'string', 'in:state,city'],
        ]);

        $keyword = BaseHelper::stringify($request->query('k'));
        $limit = (int) theme_option('limit_results_on_car_location_filter', 10) ?: 1000;
        if ($request->input('type', 'state') === 'state') {
            $locations = $stateRepository->filters($keyword, $limit);

            $carsLocationAvailable = $stateRepository->getModel()::query()
                ->wherePublished()
                ->whereExists(function ($query) {
                    $query->select('id')
                        ->from(with(new CarAddress())->getTable())
                        ->whereColumn('state_id', 'states.id');
                })
                ->pluck('id')
                ->all();
        } else {
            $locations = $cityRepository->filters($keyword, $limit);
            $locations->loadMissing('state');

            $carsLocationAvailable = $cityRepository->getModel()::query()
                ->whereExists(function ($query) {
                    $query->select('id')
                        ->from(with(new CarAddress())->getTable())
                        ->whereColumn('city_id', 'cities.id');
                })
                ->wherePublished()
                ->pluck('id')
                ->all();
        }

        $countryIds = $countryRepository->getModel()::query()
            ->wherePublished()
            ->whereExists(function ($query) {
                $query->select('id')
                    ->from(with(new CarAddress())->getTable())
                    ->whereColumn('country_id', 'countries.id')
                    ->whereNull('city_id')
                    ->whereNull('state_id');
            })
            ->where('name', 'like', '%' . $keyword . '%')
            ->pluck('id')
            ->all();

        $locations = $locations->whereIn('id', array_values(array_unique($carsLocationAvailable)));

        $locations = $locations->merge($countryRepository->getByWhereIn('id', $countryIds))->sort();

        return $response->setData([LocationResource::collection($locations), 'total' => $locations->count()]);
    }

    public function redirectToExternalBooking(string $slug)
    {
        $slug = SlugHelper::getSlug($slug, SlugHelper::getPrefix(Car::class));

        abort_unless($slug, 404);

        $car = $slug->reference;

        abort_unless($car, 404);
        abort_unless($car->hasExternalBookingUrl(), 404);

        return redirect()->away($car->external_booking_url);
    }

    public function getMake(string $slug)
    {
        $slug = SlugHelper::getSlug($slug, SlugHelper::getPrefix(CarMake::class));

        abort_unless($slug, 404);

        $carMake = $slug->reference;

        abort_if($carMake->status->getValue() !== BaseStatusEnum::PUBLISHED, 404);

        SeoHelper::setTitle(__($carMake->name));

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Cars'), route('public.cars'))
            ->add($carMake->name, $carMake->url);

        $query = Car::query()
            ->active()
            ->where('make_id', $carMake->getKey());

        $cars = $query->paginate();

        return Theme::scope('car-rentals.car-make', compact('cars', 'carMake'), 'plugins/car-rentals::themes.car-make')->render();
    }

    public function ajaxGetCarsMake(Request $request)
    {
        $requestQuery = CarListHelper::getCarFilters($request->input());

        $with = [
            'slugable',
            'transmission',
            'fuel',
            'pickupAddress',
            'make',
        ];

        $sortBy = $requestQuery['sort_by'] ?? 'recently_added';

        $cars = app(CarInterface::class)->getCars(
            $requestQuery,
            [
                'with' => $with,
                'order_by' => $sortBy,
                'paginate' => [
                    'per_page' => $requestQuery['per_page'] ?? Arr::first(CarListHelper::getPerPageParams()),
                    'current_paged' => $requestQuery['page'] ?? 1,
                ],
            ],
        );

        $additional['total'] = $cars->total();

        if ($additional['total']) {
            $message = __(':total items found', [
                'total' => $cars->total(),
            ]);
        } else {
            $message = __('No results found');
        }

        $additional['message'] = $message;

        $carsView = Theme::getThemeNamespace('views.car-rentals.car-make');

        if (! view()->exists($carsView)) {
            $carsView = 'plugins/car-rentals::themes.includes.car-items';
        }

        $carMake = CarMake::query()->findOrFail($request->input('car_make'));

        return $this
            ->httpResponse()
            ->setData(view($carsView, compact('cars', 'carMake'))->render())
            ->setAdditional($additional)
            ->setMessage($message);
    }
}
