@php
    $categories = \Botble\CarRentals\Models\CarCategory::query()->wherePublished()->get(['id', 'name'])->toArray();
@endphp

<input type="hidden" id="categories-data" value="{{ json_encode($categories) }}" />

<x-core::form.fieldset
    class="category-commission-fee-settings"
    @style(['display: none' => ! old('enable_commission_fee_for_each_category', CarRentalsHelper::isCommissionCategoryFeeBasedEnabled())])
>
    <div class="commission-setting-item-wrapper">
        @if (!empty($commissionEachCategory))
            @foreach ($commissionEachCategory as $fee => $commission)
                <div
                    class="row commission-setting-item"
                    id="commission-setting-item-{{ $loop->index }}"
                >
                    <div class="col-3">
                        <x-core::form.text-input
                            :label="trans('plugins/car-rentals::settings.commission.commission_fee')"
                            name="commission_by_category[{{ $loop->index }}][commission_fee]"
                            type="number"
                            value="{{ $fee }}"
                            min="0"
                            max="100"
                        />
                    </div>

                    <div class="col-9">
                        <x-core::form.label for="commission_fee_for_each_category">
                            {{ trans('plugins/car-rentals::settings.commission.categories') }}
                        </x-core::form.label>
                        <div class="row">
                            <div class="col-10">
                                <x-core::form.textarea
                                    class="tagify-commission-setting categories"
                                    name="commission_by_category[{{ $loop->index }}][categories]"
                                    rows="3"
                                    :value="$commission['categories'] ? json_encode($commission['categories']) : null"
                                    placeholder="{{ trans('plugins/car-rentals::settings.commission.select_categories') }}"
                                >
                                    {{ Js::from($commission['categories'], true) }}
                                </x-core::form.textarea>
                            </div>
                            <div class="col-2">
                                @if ($loop->index > 0)
                                    <x-core::button
                                        class="btn-icon"
                                        data-bb-toggle="commission-remove"
                                        data-index="{{ $loop->index }}"
                                        icon="ti ti-trash"
                                    />
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div
                class="row commission-setting-item"
                id="commission-setting-item-0"
            >
                <div class="col-3">
                    <x-core::form.text-input
                        :label="trans('plugins/car-rentals::settings.commission.commission_fee')"
                        name="commission_by_category[0][commission_fee]"
                        type="number"
                        min="0"
                        max="100"
                    />
                </div>
                <div class="col-9">
                    <x-core::form.label
                        class="form-label"
                        for="commission_fee_for_each_category"
                        :label="trans('plugins/car-rentals::settings.commission.categories')"
                    />
                    <div class="row">
                        <div class="col-10">
                            <x-core::form.textarea
                                class="tagify-commission-setting"
                                name="commission_by_category[0][categories]"
                                rows="3"
                                :value="get_car_rentals_setting('commission_by_category')"
                                placeholder="{{ trans('plugins/car-rentals::settings.commission.select_categories') }}"
                            />
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <x-core::button color="primary" data-bb-toggle="commission-category-add">
        {{ trans('plugins/car-rentals::settings.commission.add_new') }}
    </x-core::button>
</x-core::form.fieldset>
