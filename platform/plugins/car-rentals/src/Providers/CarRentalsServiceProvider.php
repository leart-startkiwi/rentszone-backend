<?php

namespace Botble\CarRentals\Providers;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Facades\EmailHandler;
use Botble\Base\Facades\PanelSectionManager;
use Botble\Base\Supports\Language;
use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\CarRentals\Facades\CarListHelper;
use Botble\CarRentals\Facades\CarRentalsHelper;
use Botble\CarRentals\Forms\Fronts\Auth\ForgotPasswordForm;
use Botble\CarRentals\Forms\Fronts\Auth\LoginForm;
use Botble\CarRentals\Forms\Fronts\Auth\RegisterForm;
use Botble\CarRentals\Forms\Fronts\Auth\ResetPasswordForm;
use Botble\CarRentals\Forms\Fronts\MessageForm;
use Botble\CarRentals\Http\Middleware\RedirectIfCustomer;
use Botble\CarRentals\Http\Middleware\RedirectIfNotCustomer;
use Botble\CarRentals\Http\Middleware\RedirectIfNotVendor;
use Botble\CarRentals\Http\Requests\Fronts\Auth\ForgotPasswordRequest;
use Botble\CarRentals\Http\Requests\Fronts\Auth\LoginRequest;
use Botble\CarRentals\Http\Requests\Fronts\Auth\RegisterRequest;
use Botble\CarRentals\Http\Requests\Fronts\Auth\ResetPasswordRequest;
use Botble\CarRentals\Http\Requests\Fronts\MessageRequest;
use Botble\CarRentals\Models\Booking;
use Botble\CarRentals\Models\Car;
use Botble\CarRentals\Models\CarCategory;
use Botble\CarRentals\Models\CarColor;
use Botble\CarRentals\Models\CarFuel;
use Botble\CarRentals\Models\CarMaintenanceHistory;
use Botble\CarRentals\Models\CarMake;
use Botble\CarRentals\Models\CarTag;
use Botble\CarRentals\Models\CarTransmission;
use Botble\CarRentals\Models\CarType;
use Botble\CarRentals\Models\Customer;
use Botble\CarRentals\Models\Service;
use Botble\CarRentals\Models\Tax;
use Botble\CarRentals\PanelSections\SettingCarRentalsPanelSection;
use Botble\CarRentals\Repositories\Eloquent\CarCategoryRepository;
use Botble\CarRentals\Repositories\Eloquent\CarRepository;
use Botble\CarRentals\Repositories\Interfaces\CarCategoryInterface;
use Botble\CarRentals\Repositories\Interfaces\CarInterface;
use Botble\LanguageAdvanced\Supports\LanguageAdvancedManager;
use Botble\Location\Facades\Location;
use Botble\Payment\Models\Payment;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Slug\Facades\SlugHelper;
use Botble\SocialLogin\Facades\SocialService;
use Botble\Theme\FormFrontManager;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Facades\Route;

class CarRentalsServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(CarCategoryInterface::class, function () {
            return new CarCategoryRepository(new CarCategory());
        });
        $this->app->bind(CarInterface::class, function () {
            return new CarRepository(new Car());
        });

        $loader = AliasLoader::getInstance();
        $loader->alias('CarListHelper', CarListHelper::class);

        config([
            'auth.guards.customer' => [
                'driver' => 'session',
                'provider' => 'customers',
            ],
            'auth.providers.customers' => [
                'driver' => 'eloquent',
                'model' => Customer::class,
            ],
            'auth.passwords.customers' => [
                'provider' => 'customers',
                'table' => 'cr_customer_password_resets',
                'expire' => 60,
            ],
        ]);

        $loader = AliasLoader::getInstance();

        $loader->alias('CarRentalsHelper', CarRentalsHelper::class);
    }

    public function boot(): void
    {
        SlugHelper::registerModule(Car::class, 'Cars');
        SlugHelper::setPrefix(Car::class, 'cars');

        SlugHelper::registerModule(CarTag::class, 'Car Tags');
        SlugHelper::setPrefix(CarTag::class, 'car-tags');

        SlugHelper::registerModule(Service::class, 'Services');
        SlugHelper::setPrefix(Service::class, 'services');

        SlugHelper::registerModule(CarCategory::class, 'Car Categories');
        SlugHelper::setPrefix(CarCategory::class, 'car-categories');

        SlugHelper::registerModule(CarMake::class, 'Car Makes');
        SlugHelper::setPrefix(CarMake::class, 'makes');

        add_filter(IS_IN_ADMIN_FILTER, [$this, 'setInAdmin'], 128);

        $this
            ->setNamespace('plugins/car-rentals')
            ->loadAndPublishViews()
            ->loadAndPublishConfigurations(['permissions', 'email', 'car-rentals', 'general'])
            ->loadAndPublishTranslations()
            ->publishAssets()
            ->loadHelpers()
            ->loadRoutes(['web', 'customer', 'fronts', 'vendor', 'base'])
            ->loadMigrations();

        DashboardMenu::default()->beforeRetrieving(function (): void {
            DashboardMenu::make()
                ->registerItem([
                    'id' => 'cms-plugins-car-rentals',
                    'priority' => 1,
                    'parent_id' => null,
                    'name' => 'plugins/car-rentals::car-rentals.name',
                    'icon' => 'ti ti-car',
                    'permissions' => ['car-rentals.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-car-rentals-booking-reports',
                    'priority' => 5,
                    'parent_id' => 'cms-plugins-car-rentals',
                    'name' => 'plugins/car-rentals::booking.reports',
                    'icon' => 'ti ti-chart-bar',
                    'route' => 'car-rentals.booking.reports.index',
                ])
                ->registerItem([
                    'id' => 'cms-plugins-car-rentals-booking-calendar',
                    'priority' => 6,
                    'parent_id' => 'cms-plugins-car-rentals',
                    'name' => 'plugins/car-rentals::booking.calendar',
                    'icon' => 'ti ti-calendar-month',
                    'route' => 'car-rentals.booking.calendar.index',
                ])
                ->registerItem([
                    'id' => 'cms-plugins-car-rentals-bookings',
                    'priority' => 10,
                    'parent_id' => 'cms-plugins-car-rentals',
                    'name' => 'plugins/car-rentals::booking.name',
                    'icon' => 'ti ti-calendar-event',
                    'route' => 'car-rentals.bookings.index',
                ])
                ->registerItem([
                    'id' => 'cms-plugins-car-rentals-service',
                    'priority' => 15,
                    'parent_id' => 'cms-plugins-car-rentals',
                    'name' => 'plugins/car-rentals::car-rentals.service.name',
                    'icon' => 'ti ti-apps',
                    'route' => 'car-rentals.services.index',
                ])
                ->registerItem([
                    'id' => 'cms-plugins-car-rentals-car',
                    'priority' => 20,
                    'parent_id' => 'cms-plugins-car-rentals',
                    'name' => 'plugins/car-rentals::car-rentals.car.name',
                    'icon' => 'ti ti-car',
                    'route' => 'car-rentals.cars.index',
                ])
                ->registerItem([
                    'id' => 'cms-plugins-car-rentals-invoices',
                    'priority' => 30,
                    'parent_id' => 'cms-plugins-car-rentals',
                    'name' => 'plugins/car-rentals::invoice.name',
                    'icon' => 'ti ti-invoice',
                    'route' => 'car-rentals.invoices.index',
                ])
                ->registerItem([
                    'id' => 'cms-plugins-car-rentals-customers',
                    'priority' => 40,
                    'parent_id' => 'cms-plugins-car-rentals',
                    'name' => 'plugins/car-rentals::car-rentals.customer.name',
                    'icon' => 'ti ti-users',
                    'route' => 'car-rentals.customers.index',
                ])
                ->registerItem([
                    'id' => 'cms-plugins-car-rentals-reviews',
                    'priority' => 45,
                    'parent_id' => 'cms-plugins-car-rentals',
                    'name' => 'plugins/car-rentals::car-rentals.review.name',
                    'icon' => 'ti ti-message',
                    'route' => 'car-rentals.reviews.index',
                ])
                ->registerItem([
                    'id' => 'cms-plugins-car-rentals-coupons',
                    'priority' => 50,
                    'parent_id' => 'cms-plugins-car-rentals',
                    'name' => 'plugins/car-rentals::car-rentals.coupon.name',
                    'icon' => 'ti ti-ticket',
                    'url' => fn () => route('car-rentals.coupons.index'),
                ])
                ->registerItem([
                    'id' => 'cms-plugins-car-rentals-taxes',
                    'priority' => 55,
                    'parent_id' => 'cms-plugins-car-rentals',
                    'name' => 'plugins/car-rentals::car-rentals.tax.name',
                    'icon' => 'ti ti-percentage',
                    'route' => 'car-rentals.taxes.index',
                ])
                ->registerItem([
                    'id' => 'cms-plugins-car-rentals-withdrawals',
                    'priority' => 60,
                    'parent_id' => 'cms-plugins-car-rentals',
                    'name' => 'plugins/car-rentals::withdrawal.name',
                    'icon' => 'ti ti-cash',
                    'route' => 'car-rentals.withdrawal.index',
                ])
                ->registerItem([
                    'id' => 'cms-plugins-car-rentals-attributes',
                    'priority' => 1,
                    'parent_id' => null,
                    'name' => 'plugins/car-rentals::car-rentals.attribute.name',
                    'icon' => 'ti ti-layout',
                    'permissions' => ['car-rentals.attributes.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-car-rentals-makes',
                    'priority' => 10,
                    'parent_id' => 'cms-plugins-car-rentals-attributes',
                    'name' => 'plugins/car-rentals::car-rentals.make.name',
                    'icon' => 'ti ti-archive',
                    'route' => 'car-rentals.car-makes.index',
                ])
                ->registerItem([
                    'id' => 'cms-plugins-car-rentals-vehicle-type',
                    'priority' => 20,
                    'parent_id' => 'cms-plugins-car-rentals-attributes',
                    'name' => 'plugins/car-rentals::car-rentals.attribute.car_type.name',
                    'icon' => 'ti ti-car',
                    'route' => 'car-rentals.car-types.index',
                ])
                ->registerItem([
                    'id' => 'cms-plugins-car-rentals-transmission',
                    'priority' => 30,
                    'parent_id' => 'cms-plugins-car-rentals-attributes',
                    'name' => 'plugins/car-rentals::car-rentals.attribute.transmission.name',
                    'icon' => 'ti ti-adjustments-alt',
                    'route' => 'car-rentals.car-transmissions.index',
                ])
                ->registerItem([
                    'id' => 'cms-plugins-car-rentals-fuel-type',
                    'priority' => 40,
                    'parent_id' => 'cms-plugins-car-rentals-attributes',
                    'name' => 'plugins/car-rentals::car-rentals.attribute.fuel_type.name',
                    'icon' => 'ti ti-affiliate',
                    'route' => 'car-rentals.car-fuels.index',
                ])
                ->registerItem([
                    'id' => 'cms-plugins-car-rentals-car-color',
                    'priority' => 50,
                    'parent_id' => 'cms-plugins-car-rentals-attributes',
                    'name' => 'plugins/car-rentals::car-rentals.attribute.color.name',
                    'icon' => 'ti ti-pencil',
                    'route' => 'car-rentals.car-colors.index',
                ])
                ->registerItem([
                    'id' => 'cms-plugins-car-rentals-car-addresses',
                    'priority' => 70,
                    'parent_id' => 'cms-plugins-car-rentals-attributes',
                    'name' => 'plugins/car-rentals::car-rentals.attribute.address.name',
                    'icon' => 'ti ti-location-pin',
                    'route' => 'car-rentals.car-addresses.index',
                ])
                ->registerItem([
                    'id' => 'cms-plugins-car-rentals-tag',
                    'priority' => 100,
                    'parent_id' => 'cms-plugins-car-rentals-attributes',
                    'name' => 'plugins/car-rentals::car-rentals.attribute.tag.name',
                    'icon' => 'ti ti-tag',
                    'route' => 'car-rentals.car-tags.index',
                ])
                ->registerItem([
                    'id' => 'cms-plugins-car-rentals-category',
                    'priority' => 200,
                    'parent_id' => 'cms-plugins-car-rentals-attributes',
                    'name' => 'plugins/car-rentals::car-rentals.attribute.category.name',
                    'icon' => 'ti ti-category',
                    'route' => 'car-rentals.car-categories.index',
                ])
                ->registerItem([
                    'id' => 'cms-plugins-car-rentals-amenity',
                    'priority' => 200,
                    'parent_id' => 'cms-plugins-car-rentals-attributes',
                    'name' => 'plugins/car-rentals::car-rentals.attribute.amenity.name',
                    'icon' => 'ti ti-cube-plus',
                    'route' => 'car-rentals.car-amenities.index',
                ])
                ->registerItem([
                    'id' => 'cms-plugins-car-rentals-messages',
                    'priority' => 1,
                    'parent_id' => null,
                    'name' => 'plugins/car-rentals::message.name',
                    'icon' => 'ti ti-mail-check',
                    fn () => route('car-rentals.message.index'),
                    'permissions' => ['car-rentals.message.index'],
                ]);
        });

        DashboardMenu::for('vendor')->beforeRetrieving(function (): void {
            DashboardMenu::make()
                ->registerItem([
                    'id' => 'car-rentals.vendor.dashboard',
                    'priority' => 1,
                    'name' => __('Dashboard'),
                    'url' => fn () => route('car-rentals.vendor.dashboard'),
                    'icon' => 'ti ti-home',
                ])
                ->registerItem([
                    'id' => 'car-rentals.vendor.cars',
                    'priority' => 10,
                    'name' => __('Cars'),
                    'url' => fn () => route('car-rentals.vendor.cars.index'),
                    'icon' => 'ti ti-car',
                ])
                ->registerItem([
                    'id' => 'car-rentals.vendor.bookings',
                    'priority' => 20,
                    'name' => __('Bookings'),
                    'url' => fn () => route('car-rentals.vendor.bookings.index'),
                    'icon' => 'ti ti-calendar-event',
                ])
                ->registerItem([
                    'id' => 'car-rentals.vendor.withdrawals',
                    'priority' => 25,
                    'name' => __('Withdrawals'),
                    'url' => fn () => route('car-rentals.vendor.withdrawals.index'),
                    'icon' => 'ti ti-cash',
                ])
                ->registerItem([
                    'id' => 'car-rentals.vendor.revenues',
                    'priority' => 26,
                    'name' => __('Revenues'),
                    'url' => fn () => route('car-rentals.vendor.revenues.index'),
                    'icon' => 'ti ti-wallet',
                ])
                ->registerItem([
                    'id' => 'cms-plugins-car-rentals-messages',
                    'priority' => 30,
                    'parent_id' => 'cms-plugins-car-rentals',
                    'name' => __('Messages'),
                    'icon' => 'ti ti-mail-check',
                    'url' => fn () => route('car-rentals.vendor.message.index'),
                ])
                ->registerItem([
                    'id' => 'car-rentals.vendor.reviews',
                    'priority' => 35,
                    'name' => __('Reviews'),
                    'url' => fn () => route('car-rentals.vendor.reviews.index'),
                    'icon' => 'ti ti-star',
                ])
                ->registerItem([
                    'id' => 'cms-plugins-car-rentals-settings',
                    'priority' => 40,
                    'parent_id' => 'cms-plugins-car-rentals',
                    'name' => __('Settings'),
                    'icon' => 'ti ti-settings',
                    'url' => fn () => route('car-rentals.vendor.settings.index'),
                ]);
        });

        PanelSectionManager::default()->beforeRendering(function (): void {
            PanelSectionManager::register(SettingCarRentalsPanelSection::class);
        });

        if (is_plugin_active('location')) {
            Location::registerModule(Car::class);
        }

        if (defined('LANGUAGE_MODULE_SCREEN_NAME') && defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')) {
            LanguageAdvancedManager::registerModule(CarTag::class, [
                'name',
                'description',
            ]);

            LanguageAdvancedManager::registerModule(CarCategory::class, [
                'name',
                'description',
            ]);

            LanguageAdvancedManager::registerModule(Tax::class, [
                'name',
            ]);

            LanguageAdvancedManager::registerModule(CarMake::class, [
                'name',
            ]);

            LanguageAdvancedManager::registerModule(Car::class, [
                'name',
                'description',
                'content',
            ]);

            LanguageAdvancedManager::registerModule(CarType::class, [
                'name',
            ]);

            LanguageAdvancedManager::registerModule(CarTransmission::class, [
                'name',
            ]);

            LanguageAdvancedManager::registerModule(CarFuel::class, [
                'name',
            ]);

            LanguageAdvancedManager::registerModule(CarColor::class, [
                'name',
            ]);

            LanguageAdvancedManager::registerModule(CarMaintenanceHistory::class, [
                'name',
            ]);

            LanguageAdvancedManager::registerModule(Service::class, [
                'name', 'content', 'description',
            ]);
        }

        $this->app->register(HookServiceProvider::class);
        $this->app->register(AssetsServiceProvider::class);

        $this->app['events']->listen(RouteMatched::class, function (): void {
            $router = $this->app['router'];

            $router->aliasMiddleware('customer', RedirectIfNotCustomer::class);
            $router->aliasMiddleware('customer.guest', RedirectIfCustomer::class);
            $router->aliasMiddleware('vendor', RedirectIfNotVendor::class);

            EmailHandler::addTemplateSettings(CAR_RENTALS_MODULE_SCREEN_NAME, config('plugins.car-rentals.email', []));
        });

        $this->app->booted(function (): void {
            SeoHelper::registerModule([CarTag::class, CarCategory::class, Car::class, CarMaintenanceHistory::class]);

            if (is_plugin_active('payment')) {
                Payment::resolveRelationUsing('order', function ($model) {
                    return $model->belongsTo(Booking::class, 'order_id')->withDefault();
                });
            }

            if (
                defined('SOCIAL_LOGIN_MODULE_SCREEN_NAME') &&
                Route::has('customer.login') &&
                Route::has('public.index')
            ) {
                SocialService::registerModule([
                    'guard' => 'customer',
                    'model' => Customer::class,
                    'login_url' => route('customer.login'),
                    'redirect_url' => BaseHelper::getHomepageUrl(),
                ]);
            }

            FormFrontManager::register(LoginForm::class, LoginRequest::class);
            FormFrontManager::register(RegisterForm::class, RegisterRequest::class);
            FormFrontManager::register(ForgotPasswordForm::class, ForgotPasswordRequest::class);
            FormFrontManager::register(ResetPasswordForm::class, ResetPasswordRequest::class);
            FormFrontManager::register(MessageForm::class, MessageRequest::class);
        });

        $this->app->register(EventServiceProvider::class);
    }

    public function setInAdmin(bool $isInAdmin): bool
    {
        $segment = request()->segment(1);

        if ($segment && in_array($segment, Language::getLocaleKeys())) {
            $segment = request()->segment(2);
        }

        return $segment === config('plugins.car-rentals.general.vendor_panel_dir', 'vendor') || $isInAdmin;
    }
}
