<?php

namespace Botble\CarRentals\Http\Controllers\Cars;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\CarRentals\Forms\CarAmenityForm;
use Botble\CarRentals\Http\Requests\CarAmenityRequest;
use Botble\CarRentals\Models\CarAmenity;
use Botble\CarRentals\Tables\CarAmenityTable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\Response;

class CarAmenityController extends BaseController
{
    public function __construct()
    {
        $this
            ->breadcrumb()
            ->add(trans('plugins/car-rentals::car-rentals.attribute.name'))
            ->add(trans('plugins/car-rentals::car-rentals.attribute.amenity.name'), route('car-rentals.car-amenities.index'));
    }

    public function index(CarAmenityTable $table): Factory|View|Response
    {
        $this->pageTitle(trans('plugins/car-rentals::car-rentals.attribute.amenity.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/car-rentals::car-rentals.attribute.amenity.create'));

        return CarAmenityForm::create()->renderForm();
    }

    public function store(CarAmenityRequest $request): BaseHttpResponse
    {
        $form = CarAmenityForm::create()->setRequest($request);
        $form->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('car-rentals.car-amenities.index'))
            ->setNextUrl(route('car-rentals.car-amenities.edit', $form->getModel()->getKey()))
            ->withCreatedSuccessMessage();
    }

    public function edit(CarAmenity $carAmenity): string
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $carAmenity->name]));

        return CarAmenityForm::createFromModel($carAmenity)->renderForm();
    }

    public function update(CarAmenity $carAmenity, CarAmenityRequest $request): BaseHttpResponse
    {
        CarAmenityForm::createFromModel($carAmenity)
            ->setRequest($request)
            ->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('car-rentals.car-amenities.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(CarAmenity $carAmenity): DeleteResourceAction
    {
        return DeleteResourceAction::make($carAmenity);
    }
}
