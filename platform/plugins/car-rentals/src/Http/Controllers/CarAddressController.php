<?php

namespace Botble\CarRentals\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\CarRentals\Forms\CarAddressForm;
use Botble\CarRentals\Http\Requests\CarAddressRequest;
use Botble\CarRentals\Models\CarAddress;
use Botble\CarRentals\Tables\CarAddressTable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\Response;

class CarAddressController extends BaseController
{
    public function __construct()
    {
        $this
            ->breadcrumb()
            ->add(trans('plugins/car-rentals::car-rentals.attribute.name'))
            ->add(trans('plugins/car-rentals::car-rentals.attribute.address.name'), route('car-rentals.car-addresses.index'));
    }

    public function index(CarAddressTable $table): Factory|View|Response
    {
        $this->pageTitle(trans('plugins/car-rentals::car-rentals.attribute.address.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/car-rentals::car-rentals.attribute.address.create'));

        return CarAddressForm::create()->renderForm();
    }

    public function store(CarAddressRequest $request): BaseHttpResponse
    {
        $form = CarAddressForm::create()
            ->setRequest($request);

        $form->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('car-rentals.car-addresses.index'))
            ->setNextUrl(route('car-rentals.car-addresses.edit', $form->getModel()->getKey()))
            ->withCreatedSuccessMessage();
    }

    public function edit(CarAddress $carAddress): string
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $carAddress->full_address]));

        return CarAddressForm::createFromModel($carAddress)->renderForm();
    }

    public function update(CarAddress $carAddress, CarAddressRequest $request): BaseHttpResponse
    {
        CarAddressForm::createFromModel($carAddress)
            ->setRequest($request)
            ->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('car-rentals.car-addresses.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(CarAddress $carAddress): DeleteResourceAction
    {
        return DeleteResourceAction::make($carAddress);
    }
}
