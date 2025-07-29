<?php

namespace Botble\CarRentals\Http\Controllers\Cars;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\CarRentals\Forms\CarTagForm;
use Botble\CarRentals\Http\Requests\CarTagRequest;
use Botble\CarRentals\Models\CarTag;
use Botble\CarRentals\Tables\CarTagTable;

class CarTagController extends BaseController
{
    public function __construct()
    {
        $this->breadcrumb()
            ->add(trans('plugins/car-rentals::car-rentals.attribute.name'))
            ->add(trans('plugins/car-rentals::car-rentals.attribute.tag.name'), route('car-rentals.car-tags.index'));
    }

    public function index(CarTagTable $table)
    {
        $this->pageTitle(trans('plugins/car-rentals::car-rentals.attribute.tag.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/car-rentals::car-rentals.attribute.tag.create'));

        return CarTagForm::create()->renderForm();
    }

    public function store(CarTagRequest $request)
    {
        $form = CarTagForm::create();
        $form->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('car-rentals.car-tags.index'))
            ->setNextUrl(route('car-rentals.car-tags.edit', $form->getModel()->getKey()))
            ->withCreatedSuccessMessage();
    }

    public function edit(CarTag $carTag)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $carTag->name]));

        return CarTagForm::createFromModel($carTag)->renderForm();
    }

    public function update(CarTag $carTag, CarTagRequest $request)
    {
        CarTagForm::createFromModel($carTag)
            ->setRequest($request)
            ->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('car-rentals.car-tags.index'))
            ->setNextUrl(route('car-rentals.car-tags.edit', $carTag->getKey()))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(CarTag $carTag)
    {
        return DeleteResourceAction::make($carTag);
    }
}
