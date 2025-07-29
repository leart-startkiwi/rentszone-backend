<?php

use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\TextField;
use Botble\Widget\AbstractWidget;
use Botble\Widget\Forms\WidgetForm;

class SocialLinksWidget extends AbstractWidget
{
    public function __construct()
    {
        parent::__construct([
            'name' => __('Social Links'),
            'description' => __('Widget display social links network'),
            'title' => __('Follow Us'),
            'items' => [],
        ]);
    }

    protected function settingForm(): WidgetForm|string|null
    {
        return WidgetForm::createFromArray($this->getConfig())
            ->add(
                'title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Title'))
            );
    }
}
