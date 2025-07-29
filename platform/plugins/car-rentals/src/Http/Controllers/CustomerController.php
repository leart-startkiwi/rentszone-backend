<?php

namespace Botble\CarRentals\Http\Controllers;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\CarRentals\Forms\CustomerForm;
use Botble\CarRentals\Http\Requests\StoreCustomerRequest;
use Botble\CarRentals\Http\Requests\UpdateCustomerRequest;
use Botble\CarRentals\Http\Resources\CustomerResource;
use Botble\CarRentals\Models\Customer;
use Botble\CarRentals\Tables\CustomerTable;
use Illuminate\Http\Request;

class CustomerController extends BaseController
{
    public function __construct()
    {
        $this
            ->breadcrumb()
            ->add(trans('plugins/car-rentals::car-rentals.name'))
            ->add(trans('plugins/car-rentals::car-rentals.customer.name'), route('car-rentals.customers.index'));
    }

    public function index(CustomerTable $table)
    {
        $this->pageTitle(trans('plugins/car-rentals::car-rentals.customer.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/car-rentals::car-rentals.customer.create'));

        return CustomerForm::create()->renderForm();
    }

    public function store(StoreCustomerRequest $request)
    {
        $form = CustomerForm::create()->setRequest($request);
        $form->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('car-rentals.customers.index'))
            ->setNextUrl(route('car-rentals.customers.edit', $form->getModel()->getKey()))
            ->withCreatedSuccessMessage();
    }

    public function edit(Customer $customer)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $customer->name]));

        $customer->password = null;

        return CustomerForm::createFromModel($customer)
            ->setValidatorClass(UpdateCustomerRequest::class)
            ->renderForm();
    }

    public function update(Customer $customer, UpdateCustomerRequest $request)
    {
        CustomerForm::createFromModel($customer)->saving(function (CustomerForm $form) use ($request): void {
            $model = $form->getModel();

            $model->update($request->validated());
        });

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('car-rentals.customers.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(Customer $customer)
    {
        return DeleteResourceAction::make($customer);
    }

    public function getList(Request $request)
    {
        $keyword = BaseHelper::stringify($request->input('q'));

        if (! $keyword) {
            return $this
                ->httpResponse()
                ->setData([]);
        }

        $data = Customer::query()
            ->orWhere('name', 'LIKE', '%' . $keyword . '%')
            ->select(['id', 'name'])
            ->take(10)
            ->get();

        return $this
            ->httpResponse()
            ->setData(CustomerResource::collection($data));
    }
}
