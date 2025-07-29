<?php

namespace Botble\CarRentals\Http\Controllers\Cars;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\CarRentals\Forms\CarFuelForm;
use Botble\CarRentals\Http\Requests\CarFuelRequest;
use Botble\CarRentals\Models\CarFuel;
use Botble\CarRentals\Tables\CarFuelTable;

class CarFuelController extends BaseController
{
    public function __construct()
    {
        $this->breadcrumb()
            ->add(trans('plugins/car-rentals::car-rentals.attribute.name'))
            ->add(trans('plugins/car-rentals::car-rentals.attribute.fuel_type.name'), route('car-rentals.car-fuels.index'));
    }

    public function index(CarFuelTable $table)
    {
        $this->pageTitle(trans('plugins/car-rentals::car-rentals.attribute.fuel_type.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/car-rentals::car-rentals.attribute.fuel_type.create'));

        return CarFuelForm::create()->renderForm();
    }

    public function store(CarFuelRequest $request)
    {
        $form = CarFuelForm::create()->setRequest($request);
        $form->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('car-rentals.car-fuels.index'))
            ->setNextUrl(route('car-rentals.car-fuels.edit', $form->getModel()->getKey()))
            ->withCreatedSuccessMessage();
    }

    public function edit(CarFuel $carFuel)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $carFuel->name]));

        return CarFuelForm::createFromModel($carFuel)->renderForm();
    }

    public function update(CarFuel $carFuel, CarFuelRequest $request)
    {
        CarFuelForm::createFromModel($carFuel)
            ->setRequest($request)
            ->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('car-rentals.car-fuels.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(CarFuel $carFuel)
    {
        return DeleteResourceAction::make($carFuel);
    }
}
