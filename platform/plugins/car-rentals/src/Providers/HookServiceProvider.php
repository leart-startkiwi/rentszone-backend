<?php

namespace Botble\CarRentals\Providers;

use Botble\Base\Facades\Form;
use Botble\Base\Facades\MetaBox;
use Botble\Base\Rules\OnOffRule;
use Botble\CarRentals\Enums\BookingStatusEnum;
use Botble\CarRentals\Events\BookingProcessingEvent;
use Botble\CarRentals\Models\Booking;
use Botble\CarRentals\Models\Car;
use Botble\CarRentals\Models\Customer;
use Botble\CarRentals\Services\BookingService;
use Botble\CarRentals\Services\GeneratePayoutInvoiceService;
use Botble\Faq\FaqSupport;
use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Http\Requests\PaymentMethodRequest;
use Botble\Payment\Models\Payment;
use Botble\Payment\Supports\PaymentHelper;
use Botble\Support\Http\Requests\Request as BaseRequest;
use Botble\Theme\Events\RenderingThemeOptionSettings;
use Illuminate\Http\Request;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class HookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        add_filter('ecommerce_invoice_templates', function (array $templates): array {
            $generateWithdrawalInvoiceService = new GeneratePayoutInvoiceService();

            return [
                ...$templates,
                'payout' => [
                    'label' => trans('plugins/car-rentals::withdrawal.invoice.invoice_template_label'),
                    'content' => fn () => $generateWithdrawalInvoiceService->getContent(),
                    'variables' => fn () => $generateWithdrawalInvoiceService->getVariables(),
                    'customized_path' => $generateWithdrawalInvoiceService->getCustomizedTemplatePath(),
                    'preview' => fn () => $generateWithdrawalInvoiceService->preview(),
                ],
            ];
        }, 999);

        $this->app['events']->listen(RouteMatched::class, function (): void {
            if (defined('PAYMENT_FILTER_REDIRECT_URL')) {
                add_filter(PAYMENT_FILTER_REDIRECT_URL, function ($checkoutToken) {
                    return route('public.checkout.success', $checkoutToken ?: session('booking_transaction_id'));
                }, 123);
            }

            if (defined('PAYMENT_FILTER_CANCEL_URL')) {
                add_filter(PAYMENT_FILTER_CANCEL_URL, function ($checkoutToken) {
                    return route('public.booking.form', ['token' => $checkoutToken ?: session('checkout_token')] + ['error' => true, 'error_type' => 'payment']);
                }, 123);
            }

            if (defined('PAYMENT_FILTER_PAYMENT_PARAMETERS')) {
                add_filter(PAYMENT_FILTER_PAYMENT_PARAMETERS, function ($html) {
                    if (! auth('customer')->check()) {
                        return $html;
                    }

                    return $html . Form::hidden('customer_id', auth('customer')->id())->toHtml() .
                        Form::hidden('customer_type', Customer::class)->toHtml();
                }, 123);
            }

            add_filter('payment_table_payer_name', function (?string $payerName, Payment $payment) {
                if ($payment->order && $payment->order->customer_name) {
                    return $payment->order->customer_name;
                }

                return $payerName;
            }, 15, 2);

            if (defined('PAYMENT_ACTION_PAYMENT_PROCESSED')) {
                add_action(PAYMENT_ACTION_PAYMENT_PROCESSED, function ($data) {
                    $orderIds = $data['order_id'];
                    $orderId = Arr::first($orderIds);

                    PaymentHelper::storeLocalPayment($data);

                    return $this->app->make(BookingService::class)->processBooking($orderId, $data['charge_id']);
                });
            }

            if (defined('PAYMENT_FILTER_PAYMENT_DATA')) {
                add_filter(PAYMENT_FILTER_PAYMENT_DATA, function (array $data, Request $request) {
                    $orderIds = (array) $request->input('order_id', []);

                    $booking = Booking::query()
                        ->with('car')
                        ->find(Arr::first($orderIds));

                    if (! $booking) {
                        return [];
                    }

                    $bookingCar = $booking->car;

                    $cars = [
                        [
                            'id' => $bookingCar->getKey(),
                            'name' => $bookingCar->car_name,
                            'image' => $bookingCar->car_mage,
                            'price' => $booking->amount,
                            'price_per_order' => $bookingCar->price,
                            'qty' => 1,
                        ],
                    ];

                    $address = [
                        'name' => $booking->customer_name,
                        'email' => $booking->customer_email,
                        'phone' => $booking->customer_phone,
                        'country' => null,
                        'state' => null,
                        'city' => null,
                        'address' => null,
                        'zip_code' => null,
                    ];

                    return [
                        'amount' => (float) $booking->amount,
                        'shipping_amount' => 0,
                        'shipping_method' => null,
                        'tax_amount' => $booking->tax_amount,
                        'discount_amount' => $booking->coupon_amount,
                        'currency' => strtoupper(get_application_currency()->title),
                        'order_id' => $orderIds,
                        'description' => trans('plugins/payment::payment.payment_description', ['order_id' => Arr::first($orderIds), 'site_url' => request()->getHost()]),
                        'customer_id' => auth('customer')->check() ? auth('customer')->id() : null,
                        'customer_type' => Customer::class,
                        'return_url' => $request->input('return_url'),
                        'callback_url' => $request->input('callback_url'),
                        'products' => $cars,
                        'orders' => [$booking],
                        'address' => $address,
                        'checkout_token' => session('checkout_token'),
                    ];
                }, 120, 2);
            }

            if (defined('PAYMENT_FILTER_PAYMENT_INFO_DETAIL')) {
                add_filter(PAYMENT_FILTER_PAYMENT_INFO_DETAIL, function ($html, $payment) {
                    if (! $payment->order_id) {
                        return $html;
                    }

                    /**
                     * @var Booking $booking
                     */
                    $booking = Booking::query()->find($payment->order_id);

                    if (! $booking) {
                        return $html;
                    }

                    return view('plugins/car-rentals::partials.payment-info', compact('booking'))->render() . $html;
                }, 123, 2);
            }

            if (defined('ACTION_AFTER_UPDATE_PAYMENT')) {
                add_action(ACTION_AFTER_UPDATE_PAYMENT, function ($request, $payment): void {
                    if (
                        in_array($payment->payment_channel, [PaymentMethodEnum::COD, PaymentMethodEnum::BANK_TRANSFER])
                        && $request->input('status') == PaymentStatusEnum::COMPLETED
                    ) {
                        /**
                         * @var Booking $booking
                         */
                        $booking = Booking::query()
                            ->where('payment_id', $payment->id)
                            ->first();

                        if (! $booking) {
                            return;
                        }

                        $booking->update(['status' => BookingStatusEnum::PROCESSING]);

                        BookingProcessingEvent::dispatch($booking);
                    }
                }, 123, 2);
            }

            add_filter('core_request_rules', function (array $rules, BaseRequest $request) {
                if ($request instanceof PaymentMethodRequest) {
                    $rules = match ($request->input('type')) {
                        PaymentMethodEnum::COD => [
                            ...$rules,
                            get_payment_setting_key('minimum_amount', PaymentMethodEnum::COD) => [
                                'nullable',
                                'numeric',
                                'min:0',
                            ],
                        ],
                        PaymentMethodEnum::BANK_TRANSFER => [
                            ...$rules,
                            get_payment_setting_key('minimum_amount', PaymentMethodEnum::BANK_TRANSFER) => [
                                'nullable',
                                'numeric',
                                'min:0',
                            ],
                            get_payment_setting_key(
                                'display_bank_info_at_the_checkout_success_page',
                                PaymentMethodEnum::BANK_TRANSFER
                            ) => [new OnOffRule()],
                        ],
                        default => $rules,
                    };
                }

                return $rules;
            }, 999, 2);

            if (
                defined('FAQ_MODULE_SCREEN_NAME')
                && config('plugins.car-rentals.general.enable_faq_in_car_details', false)
            ) {
                add_action(BASE_ACTION_META_BOXES, function ($context, $object) {
                    if (
                        ! $object
                        || $context != 'advanced'
                        || ! is_in_admin()
                        || ! $object instanceof Car
                        || ! in_array(Route::currentRouteName(), [
                            'car-rentals.cars.create',
                            'car-rentals.cars.edit',
                        ])
                    ) {
                        return false;
                    }

                    MetaBox::addMetaBox('faq_schema_config_wrapper', __('Car FAQs'), function () {
                        return (new FaqSupport())->renderMetaBox(func_get_args()[0] ?? null);
                    }, $object::class, $context);

                    return true;
                }, 139, 2);
            }
        });

        $this->app['events']->listen(RenderingThemeOptionSettings::class, function (): void {
            add_action(RENDERING_THEME_OPTIONS_PAGE, [$this, 'addThemeOptions'], 55);
        });
    }

    public function addThemeOptions(): void
    {
        theme_option()
            ->setSection([
                'title' => trans('plugins/car-rentals::car-rentals.theme_options.name'),
                'id' => 'opt-text-subsection-car-rentals',
                'subsection' => true,
                'icon' => 'ti ti-car',
                'fields' => [
                    [
                        'id' => 'car_location_filter_by',
                        'type' => 'select',
                        'label' => trans('plugins/car-rentals::car-rentals.theme_options.car_location_filter_by'),
                        'attributes' => [
                            'name' => 'car_location_filter_by',
                            'list' => [
                                'state' => trans('plugins/car-rentals::car-rentals.theme_options.state'),
                                'city' => trans('plugins/car-rentals::car-rentals.theme_options.city'),
                            ],
                            'value' => 'state',
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                    [
                        'id' => 'car_location_is_display_country',
                        'type' => 'select',
                        'label' => trans('plugins/car-rentals::car-rentals.theme_options.is_display_country'),
                        'attributes' => [
                            'name' => 'car_location_is_display_country',
                            'list' => [
                                1 => trans('plugins/car-rentals::car-rentals.theme_options.yes'),
                                0 => trans('plugins/car-rentals::car-rentals.theme_options.no'),
                            ],
                            'value' => true,
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                    [
                        'id' => 'logo_vendor_dashboard',
                        'type' => 'mediaImage',
                        'label' => trans('plugins/car-rentals::car-rentals.theme_options.logo_vendor_dashboard'),
                        'attributes' => [
                            'name' => 'logo_vendor_dashboard',
                            'value' => null,
                            'attributes' => [
                                'allow_thumb' => false,
                            ],
                        ],
                        'priority' => 1000,
                    ],
                ],
            ]);
    }
}
