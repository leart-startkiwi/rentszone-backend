<?php

namespace Botble\CarRentals\Http\Controllers\Cars;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\CarRentals\Forms\CarColorForm;
use Botble\CarRentals\Http\Requests\CarColorRequest;
use Botble\CarRentals\Models\CarColor;
use Botble\CarRentals\Tables\CarColorTable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\Response;

class CarColorController extends BaseController
{
    public function __construct()
    {
        $this
            ->breadcrumb()
            ->add(trans('plugins/car-rentals::car-rentals.attribute.name'))
            ->add(trans('plugins/car-rentals::car-rentals.attribute.color.name'), route('car-rentals.car-colors.index'));
    }

    public function index(CarColorTable $table): Factory|View|Response
    {
        $this->pageTitle(trans('plugins/car-rentals::car-rentals.attribute.color.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/car-rentals::car-rentals.attribute.color.create'));

        return CarColorForm::create()->renderForm();
    }

    public function store(CarColorRequest $request): BaseHttpResponse
    {
        $form = CarColorForm::create()->setRequest($request);
        $form->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('car-rentals.car-colors.index'))
            ->setNextUrl(route('car-rentals.car-colors.edit', $form->getModel()->getKey()))
            ->withCreatedSuccessMessage();
    }

    public function edit(CarColor $carColor): string
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $carColor->name]));

        return CarColorForm::createFromModel($carColor)->renderForm();
    }

    public function update(CarColor $carColor, CarColorRequest $request): BaseHttpResponse
    {
        CarColorForm::createFromModel($carColor)
            ->setRequest($request)
            ->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('car-rentals.car-colors.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(CarColor $carColor): DeleteResourceAction
    {
        return DeleteResourceAction::make($carColor);
    }
}
