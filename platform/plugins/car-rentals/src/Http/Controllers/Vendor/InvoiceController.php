<?php

namespace Botble\CarRentals\Http\Controllers\Vendor;

use Botble\Base\Http\Controllers\BaseController;
use Botble\CarRentals\Facades\InvoiceHelper;
use Botble\CarRentals\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends BaseController
{
    public function getGenerateInvoice(Invoice $invoice, Request $request)
    {
        if ($invoice->vendor_id !== auth('customer')->id()) {
            abort(403);
        }

        if ($request->input('type') === 'print') {
            return InvoiceHelper::streamInvoice($invoice);
        }

        return InvoiceHelper::downloadInvoice($invoice);
    }
}
