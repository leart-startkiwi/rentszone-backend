<?php

namespace Botble\CarRentals\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\CarRentals\Forms\ServiceForm;
use Botble\CarRentals\Http\Requests\ServiceRequest;
use Botble\CarRentals\Models\Service;
use Botble\CarRentals\Tables\ServiceTable;

class ServiceController extends BaseController
{
    public function __construct()
    {
        $this->breadcrumb()
            ->add(trans('plugins/car-rentals::car-rentals.name'))
            ->add(trans('plugins/car-rentals::car-rentals.service.name'), route('car-rentals.services.index'));
    }

    public function index(ServiceTable $table)
    {
        $this->pageTitle(trans('plugins/car-rentals::car-rentals.service.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/car-rentals::car-rentals.service.create'));

        return ServiceForm::create()->renderForm();
    }

    public function store(ServiceRequest $request)
    {
        $form = ServiceForm::create()->setRequest($request);
        $form->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('car-rentals.services.index'))
            ->setNextUrl(route('car-rentals.services.edit', $form->getModel()->getKey()))
            ->withCreatedSuccessMessage();
    }

    public function edit(Service $service)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $service->name]));

        return ServiceForm::createFromModel($service)->renderForm();
    }

    public function update(Service $service, ServiceRequest $request)
    {
        ServiceForm::createFromModel($service)
            ->setRequest($request)
            ->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('car-rentals.services.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(Service $service)
    {
        return DeleteResourceAction::make($service);
    }
}
