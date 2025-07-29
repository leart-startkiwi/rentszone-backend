@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <x-core::form :url="route('car-rentals.settings.invoice-template.update')" method="PUT">
        <x-core-setting::section
                :title="trans('plugins/car-rentals::settings.invoice_template.title')"
                :description="trans('plugins/car-rentals::settings.invoice_template.description')"
        >
            <x-core::form-group>
                <x-core::form.label for="content">
                    {{ trans('plugins/car-rentals::settings.invoice_template.setting_content') }}
                </x-core::form.label>

                <x-core::twig-editor
                        :variables="$variables"
                        :functions="EmailHandler::getFunctions()"
                        :value="$content"
                        name="content"
                        mode="html"
                >
                </x-core::twig-editor>
            </x-core::form-group>
        </x-core-setting::section>

        <x-core-setting::section.action>
            <div class="btn-list">
                <x-core::button
                        type="submit"
                        color="primary"
                        icon="ti ti-device-floppy"
                >
                    {{ trans('core/setting::setting.save_settings') }}
                </x-core::button>
                <x-core::button
                        tag="a"
                        href="{{ route('car-rentals.settings.invoice-template.reset') }}"
                        icon="ti ti-arrow-back-up"
                        data-bb-toggle="reset-default"
                >
                    {{ trans('core/setting::setting.email.reset_to_default') }}
                </x-core::button>
                <x-core::button
                        tag="a"
                        href="{{ route('car-rentals.settings.invoice-template.preview') }}"
                        target="_blank"
                        icon="ti ti-eye"
                        class="btn-trigger-preview-invoice-template"
                >
                    {{ trans('plugins/car-rentals::invoice.template.preview') }}
                </x-core::button>
            </div>
        </x-core-setting::section.action>
    </x-core::form>
@stop

@push('footer')
    <x-core::modal.action
            id="reset-template-to-default-modal"
            type="warning"
            :title="trans('plugins/car-rentals::settings.invoice_template.forms.confirm_reset')"
            :description="trans('plugins/car-rentals::settings.invoice_template.forms.confirm_message')"
            :submit-button-attrs="['id' => 'reset-template-to-default-button']"
            :submit-button-label="trans('plugins/car-rentals::settings.invoice_template.forms.continue')"
    />
@endpush
