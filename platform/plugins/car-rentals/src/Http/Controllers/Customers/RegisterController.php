<?php

namespace Botble\CarRentals\Http\Controllers\Customers;

use Botble\ACL\Traits\RegistersUsers;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Http\Controllers\BaseController;
use Botble\CarRentals\Facades\CarRentalsHelper;
use Botble\CarRentals\Forms\Fronts\Auth\RegisterForm;
use Botble\CarRentals\Http\Requests\Fronts\Auth\RegisterRequest;
use Botble\CarRentals\Models\Customer;
use Botble\JsValidation\Facades\JsValidator;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Theme\Facades\Theme;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;

class RegisterController extends BaseController
{
    use RegistersUsers;

    protected string $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('customer.guest');
    }

    public function showRegistrationForm()
    {
        abort_unless(CarRentalsHelper::isEnabledCustomerRegistration(), 404);

        SeoHelper::setTitle(__('Register'));

        Theme::breadcrumb()->add(__('Register'), route('customer.register'));

        if (! session()->has('url.intended') &&
            ! in_array(url()->previous(), [route('customer.login'), route('customer.register')])
        ) {
            session(['url.intended' => url()->previous()]);
        }

        Theme::asset()
            ->container('footer')
            ->usePath(false)
            ->add('js-validation', 'vendor/core/core/js-validation/js/js-validation.js', ['jquery'], version: '1.0.1');

        add_filter(THEME_FRONT_FOOTER, function ($html) {
            return $html . JsValidator::formRequest(RegisterRequest::class)->render();
        });

        return Theme::scope(
            'car-rentals.customers.register',
            ['form' => RegisterForm::create()],
            'plugins/car-rentals::themes.customers.register'
        )->render();
    }

    public function register(RegisterRequest $request)
    {
        abort_unless(CarRentalsHelper::isEnabledCustomerRegistration(), 404);

        do_action('customer_register_validation', $request);

        $customer = new Customer();

        $data = $request->validated();

        $customer->fill([
            'name' => BaseHelper::clean($data['name']),
            'email' => BaseHelper::clean($data['email']),
            'phone' => BaseHelper::clean($data['phone'] ?? null),
            'password' => Hash::make($data['password']),
        ]);

        $isEmailVerifyEnabled = CarRentalsHelper::isEnabledEmailVerification();

        $customer->confirmed_at = $isEmailVerifyEnabled ? null : Carbon::now();
        $customer->is_vendor = $request->boolean('is_vendor');
        $customer->save();

        event(new Registered($customer));

        $this->registered($request, $customer);

        if ($isEmailVerifyEnabled) {
            $customer->sendEmailVerificationNotification();

            $message = __('We have sent you an email to verify your email. Please check and confirm your email address!');

            return $this
                ->httpResponse()
                ->setNextUrl(route('customer.login'))
                ->with(['auth_warning_message' => $message])
                ->setMessage($message);
        }

        $this->guard()->login($customer);

        return $this
            ->httpResponse()
            ->setNextUrl($this->redirectPath())
            ->setMessage(__('Registered successfully!'));
    }

    protected function guard()
    {
        return auth('customer');
    }

    public function confirm(int|string $id, Request $request)
    {
        abort_unless(URL::hasValidSignature($request), 404);

        /**
         * @var Customer $customer
         */
        $customer = Customer::query()->findOrFail($id);

        $customer->confirmed_at = Carbon::now();
        $customer->save();

        $this->guard()->login($customer);

        return $this
            ->httpResponse()
            ->setNextUrl(route('customer.overview'))
            ->setMessage(__('You successfully confirmed your email address.'));
    }

    public function resendConfirmation(Request $request)
    {
        /**
         * @var Customer $customer
         */
        $customer = Customer::query()->where('email', $request->input('email'))->first();

        if (! $customer) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('Cannot find this customer!'));
        }

        $customer->sendEmailVerificationNotification();

        return $this
            ->httpResponse()
            ->setMessage(__('We sent you another confirmation email. You should receive it shortly.'));
    }
}
