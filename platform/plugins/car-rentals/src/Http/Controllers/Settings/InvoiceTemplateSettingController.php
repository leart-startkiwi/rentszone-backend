<?php

namespace Botble\CarRentals\Http\Controllers\Settings;

use Botble\Base\Facades\Assets;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\CarRentals\Http\Requests\Settings\InvoiceTemplateSettingRequest;
use Botble\CarRentals\Supports\InvoiceHelper;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;

class InvoiceTemplateSettingController extends SettingController
{
    public function edit(InvoiceHelper $invoiceHelper)
    {
        $this->pageTitle(trans('plugins/car-rentals::settings.invoice_template.title'));

        Assets::addScriptsDirectly('vendor/core/core/setting/js/email-template.js');

        $content = $invoiceHelper->getInvoiceTemplate();
        $variables = $invoiceHelper->getVariables();

        return view('plugins/car-rentals::invoices.template', compact('content', 'variables'));
    }

    public function update(InvoiceTemplateSettingRequest $request): BaseHttpResponse
    {
        BaseHelper::saveFileData(storage_path('app/templates/invoice.tpl'), $request->input('content'), false);

        return $this->httpResponse()->withUpdatedSuccessMessage();
    }

    public function reset(): BaseHttpResponse
    {
        File::delete(storage_path('app/templates/invoice.tpl'));

        return $this->httpResponse()->setMessage(trans('core/setting::setting.email.reset_success'));
    }

    public function preview(InvoiceHelper $invoiceHelper): Response
    {
        $invoice = $invoiceHelper->getDataForPreview();

        return $invoiceHelper->streamInvoice($invoice);
    }
}
