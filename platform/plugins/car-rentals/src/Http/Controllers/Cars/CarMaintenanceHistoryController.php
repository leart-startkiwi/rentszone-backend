<?php

namespace Botble\CarRentals\Http\Controllers\Cars;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\CarRentals\Forms\CarMaintenanceHistoryForm;
use Botble\CarRentals\Http\Requests\CarMaintenanceHistoryRequest;
use Botble\CarRentals\Models\CarMaintenanceHistory;

class CarMaintenanceHistoryController extends BaseController
{
    public function store(CarMaintenanceHistoryRequest $request): BaseHttpResponse
    {
        $form = CarMaintenanceHistoryForm::create()->setRequest($request);
        $form->save();

        return $this
            ->httpResponse()
            ->setNextUrl(route('car-rentals.cars.edit', $form->getModel()->car_id))
            ->withCreatedSuccessMessage();
    }

    public function edit(string|int $maintenanceHistoryId): string
    {
        $maintenanceHistory = CarMaintenanceHistory::query()->whereKey($maintenanceHistoryId)->firstOrFail();

        return CarMaintenanceHistoryForm::createFromModel($maintenanceHistory)
            ->setUrl(route('car-rentals.car-maintenance-histories.update', $maintenanceHistory))
            ->renderForm();
    }

    public function update(string|int $maintenanceHistoryId, CarMaintenanceHistoryRequest $request): BaseHttpResponse
    {
        $maintenanceHistory = CarMaintenanceHistory::query()->whereKey($maintenanceHistoryId)->firstOrFail();

        CarMaintenanceHistoryForm::createFromModel($maintenanceHistory)
            ->setRequest($request)
            ->save();

        return $this
            ->httpResponse()
            ->setNextUrl(route('car-rentals.cars.edit', $maintenanceHistory->car_id))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(string|int $maintenanceHistoryId): DeleteResourceAction
    {
        $maintenanceHistory = CarMaintenanceHistory::query()->whereKey($maintenanceHistoryId)->firstOrFail();

        return DeleteResourceAction::make($maintenanceHistory);
    }
}
