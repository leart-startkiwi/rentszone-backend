<?php

namespace Botble\CarRentals\PanelSections;

use Botble\Base\PanelSections\PanelSection;
use Botble\Base\PanelSections\PanelSectionItem;

class SettingCarRentalsPanelSection extends PanelSection
{
    public function setup(): void
    {
        $this
            ->setId('settings.car_rentals')
            ->setTitle(trans('plugins/car-rentals::settings.name'))
            ->withPriority(1000)
            ->withItems([
                PanelSectionItem::make('settings.car_rentals.general_settings')
                    ->setTitle(trans('plugins/car-rentals::settings.general.title'))
                    ->withIcon('ti ti-settings')
                    ->withDescription(trans('plugins/car-rentals::settings.general.description'))
                    ->withPriority(10)
                    ->withRoute('car-rentals.settings.general'),
                PanelSectionItem::make('settings.car_rentals.car_filter_settings')
                    ->setTitle(trans('plugins/car-rentals::settings.car_filter.title'))
                    ->withIcon('ti ti-search')
                    ->withDescription(trans('plugins/car-rentals::settings.car_filter.description'))
                    ->withPriority(20)
                    ->withRoute('car-rentals.settings.car-filters'),
                PanelSectionItem::make('settings.car_rentals.review_settings')
                    ->setTitle(trans('plugins/car-rentals::settings.review.title'))
                    ->withIcon('ti ti-settings')
                    ->withDescription(trans('plugins/car-rentals::settings.review.description'))
                    ->withPriority(30)
                    ->withRoute('car-rentals.settings.reviews'),
                PanelSectionItem::make('settings.car_rentals.currency_settings')
                    ->setTitle(trans('plugins/car-rentals::settings.currency.title'))
                    ->withIcon('ti ti-coin')
                    ->withDescription(trans('plugins/car-rentals::settings.currency.description'))
                    ->withPriority(40)
                    ->withRoute('car-rentals.settings.currencies'),
                PanelSectionItem::make('settings.car_rentals.invoice_settings')
                    ->setTitle(trans('plugins/car-rentals::settings.invoice.title'))
                    ->withIcon('ti ti-file-invoice')
                    ->withDescription(trans('plugins/car-rentals::settings.invoice.description'))
                    ->withPriority(50)
                    ->withRoute('car-rentals.settings.invoices'),
                PanelSectionItem::make('settings.car_rentals.invoice_template_settings')
                    ->setTitle(trans('plugins/car-rentals::settings.invoice_template.title'))
                    ->withIcon('ti ti-file-invoice')
                    ->withDescription(trans('plugins/car-rentals::settings.invoice_template.description'))
                    ->withPriority(60)
                    ->withRoute('car-rentals.settings.invoice-template'),
                PanelSectionItem::make('car-rentals.settings.customer_settings')
                    ->setTitle(trans('plugins/car-rentals::settings.customer.title'))
                    ->withIcon('ti ti-users')
                    ->withDescription(trans('plugins/car-rentals::settings.customer.description'))
                    ->withPriority(70)
                    ->withRoute('car-rentals.settings.customers'),
                PanelSectionItem::make('car-rentals.settings.tax_settings')
                    ->setTitle(trans('plugins/car-rentals::settings.tax.name'))
                    ->withIcon('ti ti-receipt-tax')
                    ->withDescription(trans('plugins/car-rentals::settings.tax.description'))
                    ->withPriority(180)
                    ->withRoute('car-rentals.settings.taxes'),
            ]);
    }
}
