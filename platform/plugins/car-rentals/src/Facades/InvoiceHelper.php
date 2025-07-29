<?php

namespace Botble\CarRentals\Facades;

use Botble\CarRentals\Supports\InvoiceHelper as BaseInvoiceHelper;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Botble\CarRentals\Models\Invoice store(\Botble\CarRentals\Models\Booking $booking)
 * @method static \Botble\CarRentals\Models\Invoice getDataForPreview()
 * @method static string getLanguageSupport()
 * @method static string getInvoiceTemplatePath()
 * @method static string getInvoiceTemplateCustomizedPath()
 * @method static string getInvoiceTemplate()
 * @method static array getVariables()
 * @method static \Illuminate\Http\Response downloadInvoice($invoice)
 * @method static \Illuminate\Http\Response streamInvoice(\Botble\CarRentals\Models\Invoice $invoice)
 * @method static \Barryvdh\DomPDF\PDF makeInvoice(\Botble\CarRentals\Models\Invoice $invoice)
 *
 * @see \Botble\CarRentals\Supports\InvoiceHelper
 */
class InvoiceHelper extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return BaseInvoiceHelper::class;
    }
}
