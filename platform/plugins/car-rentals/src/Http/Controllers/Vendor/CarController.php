<?php

namespace Botble\CarRentals\Http\Controllers\Vendor;

use Botble\Base\Facades\EmailHandler;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\CarRentals\Enums\ModerationStatusEnum;
use Botble\CarRentals\Facades\CarRentalsHelper;
use Botble\CarRentals\Forms\Vendor\CarForm;
use Botble\CarRentals\Http\Requests\Vendor\CarRequest;
use Botble\CarRentals\Models\Car;
use Botble\CarRentals\Models\Customer;
use Botble\CarRentals\Tables\Vendor\CarTable;
use Illuminate\Http\Request;

class CarController extends BaseController
{
    public function index(CarTable $carTable)
    {
        $this->pageTitle(trans('plugins/car-rentals::car-rentals.car.name'));

        return $carTable->render('plugins/car-rentals::themes.vendor-dashboard.table.base');
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/car-rentals::car-rentals.car.create'));

        return CarForm::create()
            ->renderForm();
    }

    public function store(CarRequest $request)
    {
        $carForm = CarForm::create()->setRequest($request);

        $carForm->saving(function (CarForm $form): void {
            $request = $form->getRequest();

            /**
             * @var Car $car
             */
            $car = $form->getModel();

            $car->fill(array_merge($this->processRequestData($request), [
                'author_id' => auth('customer')->id(),
                'author_type' => Customer::class,
            ]));

            if (! CarRentalsHelper::isEnabledPostApproval()) {
                $car->moderation_status = ModerationStatusEnum::APPROVED;
            }

            $car->save();

            $form->fireModelEvents($car);

            EmailHandler::setModule(CAR_RENTALS_MODULE_SCREEN_NAME)
                ->setVariableValues([
                    'post_name' => $car->name,
                    'post_url' => route('car-rentals.cars.edit', $car->getKey()),
                    'post_author' => $car->author->name,
                ])
                ->sendUsingTemplate('new-pending-car');
        });

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('car-rentals.vendor.cars.index'))
            ->setNextUrl(route('car-rentals.vendor.cars.edit', $carForm->getModel()->getKey()))
            ->withCreatedSuccessMessage();
    }

    public function edit(Car $car)
    {
        if ($car->author_type != Customer::class || $car->author_id != auth('customer')->id()) {
            abort(403);
        }

        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $car->name]));

        return CarForm::createFromModel($car)
            ->renderForm();
    }

    public function update(Car $car, CarRequest $request)
    {
        if ($car->author_type != Customer::class || $car->author_id != auth('customer')->id()) {
            abort(403);
        }

        $carForm = CarForm::createFromModel($car)->setRequest($request);

        $carForm->saving(function (CarForm $form): void {
            $request = $form->getRequest();

            /**
             * @var Car $car
             */
            $car = $form->getModel();

            $car->fill($this->processRequestData($request));

            $car->save();

            $form->fireModelEvents($car);
        });

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('car-rentals.vendor.cars.index'))
            ->setNextUrl(route('car-rentals.vendor.cars.edit', $car->id))
            ->withUpdatedSuccessMessage();
    }

    protected function processRequestData(Request $request): array
    {
        $shortcodeCompiler = shortcode()->getCompiler();

        $request->merge([
            'content' => $shortcodeCompiler->strip(
                $request->input('content'),
                $shortcodeCompiler->whitelistShortcodes()
            ),
        ]);

        $except = [
            'is_featured',
            'author_id',
            'author_type',
            'moderation_status',
        ];

        foreach ($except as $item) {
            $request->request->remove($item);
        }

        return $request->input();
    }

    public function destroy(Car $car): DeleteResourceAction
    {
        if ($car->author_type != Customer::class || $car->author_id != auth('customer')->id()) {
            abort(403);
        }

        return DeleteResourceAction::make($car);
    }
}
