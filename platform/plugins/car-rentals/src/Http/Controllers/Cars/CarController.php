<?php

namespace Botble\CarRentals\Http\Controllers\Cars;

use Botble\Base\Facades\EmailHandler;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\CarRentals\Enums\ModerationStatusEnum;
use Botble\CarRentals\Forms\CarForm;
use Botble\CarRentals\Http\Requests\CarRequest;
use Botble\CarRentals\Models\Car;
use Botble\CarRentals\Models\CarTag;
use Botble\CarRentals\Tables\CarTable;
use Illuminate\Http\Request;

class CarController extends BaseController
{
    public function __construct()
    {
        $this->breadcrumb()
            ->add(trans('plugins/car-rentals::car-rentals.name'))
            ->add(trans('plugins/car-rentals::car-rentals.car.name'), route('car-rentals.cars.index'));
    }

    public function index(CarTable $table)
    {
        $this->pageTitle(trans('plugins/car-rentals::car-rentals.car.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/car-rentals::car-rentals.car.create'));

        return CarForm::create()->renderForm();
    }

    public function store(CarRequest $request)
    {
        $form = CarForm::create()->setRequest($request);
        $form->saving(function (CarForm $form) use ($request): void {
            /**
             * @var Car $model
             */
            $model = $form->getModel();

            $dataCreate = $request->validated();

            if ($request->boolean('is_same_drop_off') && ! empty($dataCreate['pick_address_id'])) {
                $dataCreate['return_address_id'] = $dataCreate['pick_address_id'];
            }

            $model->fill($dataCreate);

            $model->images = array_filter($request->input('images', []));
            $model->moderation_status = ModerationStatusEnum::APPROVED;

            $model->save();

            $tags = $request->input('tags');

            $tags = $tags ? explode(',', $tags) : [];

            $tagIds = CarTag::query()->wherePublished()->whereIn('id', $tags)->pluck('id')->all();

            if ($tagIds) {
                $model->tags()->sync($tagIds);
            }

            $model->categories()->sync($request->input('categories', []));

            $colors = $request->input('colors');

            $colors = $colors ? explode(',', $colors) : [];

            if ($colors) {
                $model->colors()->sync($colors);
            }

            $model->amenities()->sync($request->input('amenities', []));
        });

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('car-rentals.cars.index'))
            ->setNextUrl(route('car-rentals.cars.edit', $form->getModel()->getKey()))
            ->withCreatedSuccessMessage();
    }

    public function edit(Car $car)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $car->name]));

        return CarForm::createFromModel($car)->renderForm();
    }

    public function update(Car $car, CarRequest $request)
    {
        CarForm::createFromModel($car)->saving(function (CarForm $form) use ($request): void {
            /**
             * @var Car $model
             */
            $model = $form->getModel();
            $dataUpdate = $request->validated();
            if ($request->boolean('is_same_drop_off') && ! empty($dataUpdate['pick_address_id'])) {
                $dataUpdate['return_address_id'] = $dataUpdate['pick_address_id'];
            }

            $model->fill($dataUpdate);
            $model->images = array_filter($request->input('images', []));

            $model->save();

            $tags = $request->input('tags');

            $tags = $tags ? explode(',', $tags) : [];

            $tagIds = CarTag::query()->wherePublished()->whereIn('id', $tags)->pluck('id')->all();

            $model->tags()->sync($tagIds);

            $model->categories()->sync($request->input('categories', []));

            $colors = $request->input('colors');

            $colors = $colors ? explode(',', $colors) : [];

            $model->colors()->sync($colors);

            $model->amenities()->sync($request->input('amenities', []));
        });

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('car-rentals.cars.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(Car $car): DeleteResourceAction
    {
        return DeleteResourceAction::make($car);
    }

    public function approve(Car $car)
    {
        abort_unless($car->is_pending_moderation, 404);

        $car->moderation_status = ModerationStatusEnum::APPROVED;
        $car->save();

        EmailHandler::setModule(CAR_RENTALS_MODULE_SCREEN_NAME)
            ->setVariableValues([
                'author_name' => $car->author->name,
                'car_name' => $car->name,
                'car_link' => route('car-rentals.vendor.cars.edit', $car->getKey()),
            ])
            ->sendUsingTemplate('car-approved', $car->author->email);

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('car-rentals.cars.index'))
            ->setMessage(trans('plugins/car-rentals::car-rentals.car.forms.status_moderation.approved'));
    }

    public function reject(Car $car, Request $request)
    {
        abort_unless($car->is_pending_moderation, 404);

        $request->validate([
            'reason' => ['required', 'string', 'max:400'],
        ]);

        $car->moderation_status = ModerationStatusEnum::REJECTED;
        $car->reject_reason = $request->input('reason');
        $car->save();

        EmailHandler::setModule(CAR_RENTALS_MODULE_SCREEN_NAME)
            ->setVariableValues([
                'author_name' => $car->author->name,
                'car_name' => $car->name,
                'car_link' => route('car-rentals.vendor.cars.edit', $car->getKey()),
                'reason' => $request->input('reason'),
            ])
            ->sendUsingTemplate('car-rejected', $car->author->email);

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('car-rentals.cars.index'))
            ->setMessage(trans('plugins/car-rentals::car-rentals.car.forms.status_moderation.rejected'));
    }
}
