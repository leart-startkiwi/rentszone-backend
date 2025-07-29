<?php

namespace Botble\CarRentals\Http\Controllers\Cars;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\CarRentals\Forms\CarTransmissionForm;
use Botble\CarRentals\Http\Requests\CarTransmissionRequest;
use Botble\CarRentals\Models\CarTransmission;
use Botble\CarRentals\Tables\CarTransmissionTable;

class CarTransmissionController extends BaseController
{
    public function __construct()
    {
        $this->breadcrumb()
            ->add(trans('plugins/car-rentals::car-rentals.attribute.name'))
            ->add(trans('plugins/car-rentals::car-rentals.attribute.transmission.name'), route('car-rentals.car-transmissions.index'));
    }

    public function index(CarTransmissionTable $table)
    {
        $this->pageTitle(trans('plugins/car-rentals::car-rentals.attribute.transmission.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/car-rentals::car-rentals.attribute.transmission.create'));

        return CarTransmissionForm::create()->renderForm();
    }

    public function store(CarTransmissionRequest $request)
    {
        $form = CarTransmissionForm::create()->setRequest($request);
        $form->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('car-rentals.car-transmissions.index'))
            ->setNextUrl(route('car-rentals.car-transmissions.edit', $form->getModel()->getKey()))
            ->withCreatedSuccessMessage();
    }

    public function edit(CarTransmission $carTransmission)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $carTransmission->name]));

        return CarTransmissionForm::createFromModel($carTransmission)->renderForm();
    }

    public function update(CarTransmission $carTransmission, CarTransmissionRequest $request)
    {
        CarTransmissionForm::createFromModel($carTransmission)
            ->setRequest($request)
            ->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('car-rentals.car-transmissions.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(CarTransmission $carTransmission)
    {
        return DeleteResourceAction::make($carTransmission);
    }
}
