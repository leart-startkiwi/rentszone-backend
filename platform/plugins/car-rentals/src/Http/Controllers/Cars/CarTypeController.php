<?php

namespace Botble\CarRentals\Http\Controllers\Cars;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\CarRentals\Forms\CarTypeForm;
use Botble\CarRentals\Http\Requests\CarTypeRequest;
use Botble\CarRentals\Models\CarType;
use Botble\CarRentals\Tables\CarTypeTable;

class CarTypeController extends BaseController
{
    public function __construct()
    {
        $this->breadcrumb()
            ->add(trans('plugins/car-rentals::car-rentals.attribute.name'))
            ->add(trans('plugins/car-rentals::car-rentals.attribute.car_type.name'), route('car-rentals.car-types.index'));
    }

    public function index(CarTypeTable $table)
    {
        $this->pageTitle(trans('plugins/car-rentals::car-rentals.attribute.car_type.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/car-rentals::car-rentals.attribute.car_type.create'));

        return CarTypeForm::create()->renderForm();
    }

    public function store(CarTypeRequest $request)
    {
        $form = CarTypeForm::create()->setRequest($request);
        $form->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('car-rentals.car-types.index'))
            ->setNextUrl(route('car-rentals.car-types.edit', $form->getModel()->getKey()))
            ->withCreatedSuccessMessage();
    }

    public function edit(CarType $carType)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $carType->name]));

        return CarTypeForm::createFromModel($carType)->renderForm();
    }

    public function update(CarType $carType, CarTypeRequest $request)
    {
        CarTypeForm::createFromModel($carType)
            ->setRequest($request)
            ->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('car-rentals.car-types.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(CarType $carType)
    {
        return DeleteResourceAction::make($carType);
    }
}
