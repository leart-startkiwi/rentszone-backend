<?php

namespace Botble\CarRentals\Http\Controllers\Cars;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\CarRentals\Forms\CarMakeForm;
use Botble\CarRentals\Http\Requests\CarMakeRequest;
use Botble\CarRentals\Models\CarMake;
use Botble\CarRentals\Tables\CarMakeTable;

class CarMakeController extends BaseController
{
    public function __construct()
    {
        $this->breadcrumb()
            ->add(trans('plugins/car-rentals::car-rentals.attribute.name'))
            ->add(trans('plugins/car-rentals::car-rentals.make.name'), route('car-rentals.car-makes.index'));
    }

    public function index(CarMakeTable $table)
    {
        $this->pageTitle(trans('plugins/car-rentals::car-rentals.make.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/car-rentals::car-rentals.make.create'));

        return CarMakeForm::create()->renderForm();
    }

    public function store(CarMakeRequest $request)
    {
        $form = CarMakeForm::create()->setRequest($request);
        $form->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('car-rentals.car-makes.index'))
            ->setNextUrl(route('car-rentals.car-makes.edit', $form->getModel()->getKey()))
            ->withCreatedSuccessMessage();
    }

    public function edit(CarMake $carMake)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $carMake->name]));

        return CarMakeForm::createFromModel($carMake)->renderForm();
    }

    public function update(CarMake $carMake, CarMakeRequest $request)
    {
        CarMakeForm::createFromModel($carMake)
            ->setRequest($request)
            ->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('car-rentals.car-makes.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(CarMake $carMake)
    {
        return DeleteResourceAction::make($carMake);
    }
}
