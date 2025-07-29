<?php

use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\TextField;
use Botble\Newsletter\Forms\Fronts\NewsletterForm;
use Botble\Widget\AbstractWidget;
use Botble\Widget\Forms\WidgetForm;
use Illuminate\Support\Arr;

class NewsletterWidget extends AbstractWidget
{
    public function __construct()
    {
        parent::__construct([
            'name' => __('Newsletter'),
            'description' => __('Add a newsletter to your widget area.'),
            'title' => null,
            'subtitle' => null,
            'image' => null,
            'position' => 'footer',
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
            )
            ->add(
                'button_label',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Button Label'))
            );

    }

    public function data(): array
    {
        $label = Arr::get($this->getConfig(), 'button_label') ?: __('Subscribe');

        $form = NewsletterForm::create()
            ->formClass('form-newsletter wow fadeInUp')
            ->remove(['wrapper_before', 'wrapper_after'])
            ->modify('submit', 'submit', ['attr' => ['class' => 'btn btn-brand-2'], 'label' => $label], true);

        return compact('form');
    }

    protected function requiredPlugins(): array
    {
        return ['newsletter'];
    }
}
