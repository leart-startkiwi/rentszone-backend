<?php

use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Shortcode\Forms\FieldOptions\ShortcodeTabsFieldOption;
use Botble\Shortcode\Forms\Fields\ShortcodeTabsField;
use Botble\Widget\AbstractWidget;
use Botble\Widget\Forms\WidgetForm;

class SiteInformationWidget extends AbstractWidget
{
    public function __construct()
    {
        parent::__construct([
            'name' => __('Site Information'),
            'description' => __('Add a site information to your widget area.'),
            'items' => [],
        ]);
    }

    protected function settingForm(): WidgetForm|string|null
    {
        return WidgetForm::createFromArray($this->getConfig())
            ->add(
                'logo',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Logo'))
                    ->helperText(__('Leave blank to use the default logo.'))
            )
            ->add(
                'items',
                ShortcodeTabsField::class,
                ShortcodeTabsFieldOption::make()
                    ->fields([
                        'title' => [
                            'type' => 'text',
                            'title' => __('Title'),
                        ],
                        'description' => [
                            'type' => 'textarea',
                            'title' => __('Description'),
                        ],
                        'icon' => [
                            'title' => __('Icon'),
                            'type' => 'coreIcon',
                        ],
                        'icon_image' => [
                            'title' => __('Icon image'),
                            'type' => 'image',
                        ],
                    ])
                    ->attrs($this->getConfig())
            );
    }
}
