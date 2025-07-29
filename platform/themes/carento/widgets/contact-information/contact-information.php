<?php

use Botble\Shortcode\Forms\FieldOptions\ShortcodeTabsFieldOption;
use Botble\Shortcode\Forms\Fields\ShortcodeTabsField;
use Botble\Widget\AbstractWidget;
use Botble\Widget\Forms\WidgetForm;

class ContactInformationWidget extends AbstractWidget
{
    public function __construct()
    {
        parent::__construct([
            'name' => __('Contact Information'),
            'description' => __('Add a contact information to your widget area.'),
            'items' => [],
        ]);
    }

    protected function settingForm(): WidgetForm|string|null
    {
        return WidgetForm::createFromArray($this->getConfig())
            ->add(
                'items',
                ShortcodeTabsField::class,
                ShortcodeTabsFieldOption::make()
                    ->fields([
                        'title' => [
                            'title' => __('Title'),
                        ],
                        'icon' => [
                            'title' => __('Icon'),
                            'type' => 'coreIcon',
                        ],
                        'icon_image' => [
                            'title' => __('Icon image'),
                            'type' => 'image',
                        ],
                        'url' => [
                            'title' => __('URL'),
                        ],
                    ])
                    ->attrs($this->getConfig())
            );
    }
}
