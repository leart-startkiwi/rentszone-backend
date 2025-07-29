<?php

namespace Botble\CarRentals\Http\Controllers;

use Botble\Base\Facades\Assets;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\CarRentals\Facades\InvoiceHelper;
use Botble\CarRentals\Models\Invoice;
use Botble\CarRentals\Tables\InvoiceTable;
use Illuminate\Http\Request;

class InvoiceController extends BaseController
{
    public function __construct()
    {
        $this
            ->breadcrumb()
            ->add(trans('plugins/car-rentals::car-rentals.name'))
            ->add(trans('plugins/car-rentals::invoice.name'), route('car-rentals.invoices.index'));
    }

    public function index(InvoiceTable $table)
    {
        $this->pageTitle(trans('plugins/car-rentals::invoice.name'));

        Assets::addScripts(['bootstrap-editable'])
            ->addStyles(['bootstrap-editable']);

        return $table->renderTable();
    }

    public function edit(Invoice $invoice)
    {
        $this->pageTitle(trans('plugins/car-rentals::invoice.show', ['code' => $invoice->code]));

        return view('plugins/car-rentals::invoices.show', compact('invoice'));
    }

    public function destroy(Invoice $invoice)
    {
        return DeleteResourceAction::make($invoice);
    }

    public function getGenerateInvoice(int|string $id, Request $request)
    {
        $invoice = Invoice::query()->findOrFail($id);

        if ($request->input('type') === 'print') {
            return InvoiceHelper::streamInvoice($invoice);
        }

        return InvoiceHelper::downloadInvoice($invoice);
    }
}
