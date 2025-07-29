<?php

namespace Botble\CarRentals\Http\Controllers\Customers;

use Botble\ACL\Http\Requests\UpdatePasswordRequest;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\CarRentals\Facades\InvoiceHelper;
use Botble\CarRentals\Forms\Fronts\Auth\ChangePasswordForm;
use Botble\CarRentals\Forms\Fronts\Customers\CustomerForm;
use Botble\CarRentals\Http\Requests\AvatarRequest;
use Botble\CarRentals\Http\Requests\Fronts\Customers\EditCustomerRequest;
use Botble\CarRentals\Models\Booking;
use Botble\CarRentals\Models\CarReview;
use Botble\CarRentals\Models\Invoice;
use Botble\Media\Facades\RvMedia;
use Botble\Media\Services\ThumbnailService;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Theme\Facades\Theme;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class PublicController extends BaseController
{
    public function __construct()
    {
        Theme::asset()
            ->add('customer-style', 'vendor/core/plugins/car-rentals/css/customer.css');

        if (BaseHelper::isRtlEnabled()) {
            Theme::asset()
                ->add('customer-rtl-style', 'vendor/core/plugins/car-rentals/css/customer-rtl.css');
        }

        Theme::asset()
            ->container('footer')
            ->add('cropper-js', 'vendor/core/plugins/car-rentals/libraries/cropper/cropper.min.js', ['jquery'])
            ->add('avatar-js', 'vendor/core/plugins/car-rentals/js/avatar.js', ['jquery']);

        Theme::breadcrumb()
            ->add(__('Account'), route('customer.overview'));
    }

    public function getOverView()
    {
        SeoHelper::setTitle(__('Account information'));

        Theme::breadcrumb()
            ->add(__('Overview'), route('customer.overview'));

        return Theme::scope('car-rentals.customers.overview', [], 'plugins/car-rentals::themes.customers.overview')
            ->render();
    }

    public function getEditProfile()
    {
        SeoHelper::setTitle(__('Profile'));

        $customer = Auth::guard('customer')->user();

        Theme::breadcrumb()
            ->add(__('Profile'), route('customer.profile'));

        return Theme::scope('car-rentals.customers.profile', ['form' => CustomerForm::createFromModel($customer)], 'plugins/car-rentals::themes.customers.profile')
            ->render();
    }

    public function postEditProfile(EditCustomerRequest $request)
    {
        $customer = Auth::guard('customer')->user();
        CustomerForm::createFromModel($customer)->saving(function (CustomerForm $form) use ($request): void {
            $model = $form->getModel();

            $model->fill($request->except('email'));

            $model->save();
        });

        return $this
            ->httpResponse()
            ->setNextUrl(route('customer.profile'))
            ->setMessage(__('Update profile successfully!'));
    }

    public function getChangePassword()
    {
        SeoHelper::setTitle(__('Change password'));

        Theme::breadcrumb()
            ->add(__('Change Password'), route('customer.change-password'));

        return Theme::scope('car-rentals.customers.change-password', ['form' => ChangePasswordForm::create()], 'plugins/car-rentals::themes.customers.change-password')
            ->render();
    }

    public function postChangePassword(UpdatePasswordRequest $request)
    {
        $customer = Auth::guard('customer')->user();

        ChangePasswordForm::createFromModel($customer)
            ->setRequest($request)
            ->saving(function (ChangePasswordForm $form): void {
                $model = $form->getModel();
                $request = $form->getRequest();

                $model->update([
                    'password' => Hash::make($request->input('password')),
                ]);
            });

        return $this
            ->httpResponse()
            ->setMessage(trans('core/acl::users.password_update_success'));
    }

    public function postAvatar(AvatarRequest $request, ThumbnailService $thumbnailService, BaseHttpResponse $response)
    {
        try {
            $account = auth('customer')->user();

            $result = RvMedia::handleUpload($request->file('avatar_file'), 0, $account->upload_folder);

            if ($result['error']) {
                return $response->setError()->setMessage($result['message']);
            }

            $avatarData = json_decode($request->input('avatar_data'));

            $file = $result['data'];

            $thumbnailService
                ->setImage(RvMedia::getRealPath($file->url))
                ->setSize((int) $avatarData->width, (int) $avatarData->height)
                ->setCoordinates((int) $avatarData->x, (int) $avatarData->y)
                ->setDestinationPath(File::dirname($file->url))
                ->setFileName(File::name($file->url) . 'Front' . File::extension($file->url))
                ->save('crop');

            $account->avatar = $file->url;
            $account->save();

            return $response
                ->setMessage(trans('plugins/customer::dashboard.update_avatar_success'))
                ->setData(['url' => RvMedia::url($file->url)]);
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function getBookings()
    {
        SeoHelper::setTitle(__('Bookings'));

        Theme::breadcrumb()
            ->add(__('Bookings'), route('customer.bookings'));

        $customer = Auth::guard('customer')->user();

        $bookings = Booking::query()
            ->where('customer_id', $customer->id)
            ->orderBy('id', 'desc')
            ->paginate();

        return Theme::scope('car-rentals.customers.bookings.list', compact('bookings'), 'plugins/car-rentals::themes.customers.bookings.list')
            ->render();
    }

    public function getBookingDetail(int|string $transactionId)
    {
        $booking = Booking::query()
            ->with('invoice')
            ->where([
                'transaction_id' => $transactionId,
                'customer_id' => auth('customer')->id(),
            ])
            ->firstOrFail();

        SeoHelper::setTitle(__('Booking Information'));

        Theme::breadcrumb()
            ->add(
                __('Booking Information'),
                route('customer.bookings.show', $transactionId)
            );

        return Theme::scope(
            'car-rentals.customers.bookings.detail',
            ['booking' => $booking, 'route' => 'customer.invoices.generate'],
            'plugins/car-rentals::themes.customers.bookings.detail'
        )->render();
    }

    public function getGenerateInvoice(int|string $invoiceId, Request $request)
    {
        $invoice = Invoice::query()->findOrFail($invoiceId);

        abort_unless($this->canViewInvoice($invoice), 404);

        if ($request->input('type') === 'print') {
            return InvoiceHelper::streamInvoice($invoice);
        }

        return InvoiceHelper::downloadInvoice($invoice);
    }

    protected function canViewInvoice(Invoice $invoice): bool
    {
        return auth('customer')->id() == $invoice->payment->customer_id;
    }

    public function getReviews()
    {
        SeoHelper::setTitle(__('Reviews'));

        $reviews = CarReview::query()
            ->where([
                'customer_id' => auth('customer')->id(),
            ])
            ->with('car')
            ->orderByDesc('created_at')
            ->paginate(5);

        Theme::breadcrumb()
            ->add(__('Reviews'), route('customer.reviews'));

        return Theme::scope(
            'car-rentals.customers.reviews',
            compact('reviews'),
            'plugins/car-rentals::themes.customers.reviews'
        )->render();
    }
}
