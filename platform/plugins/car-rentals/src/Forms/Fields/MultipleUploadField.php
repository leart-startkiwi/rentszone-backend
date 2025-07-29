<?php

namespace Botble\CarRentals\Forms\Fields;

use Botble\Base\Facades\Assets;
use Botble\Base\Forms\FormField;

class MultipleUploadField extends FormField
{
    protected function getTemplate(): string
    {
        Assets::addScripts(['dropzone'])
            ->addStyles(['dropzone']);

        return 'plugins/car-rentals::themes.vendor-dashboard.forms.fields.multiple-upload';
    }
}
