<?php

namespace Theme\Carento\FormDecorators;

use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Shortcode\Forms\ShortcodeForm;
use Botble\Widget\Forms\WidgetForm;

class PostFormDecorator
{
    public static function createFrom(
        WidgetForm|ShortcodeForm $form,
        ?string $addAfter = null,
        bool $hasLimitation = true
    ): WidgetForm|ShortcodeForm {
        $params = [
            'title',
            TextField::class,
            TextFieldOption::make()
                ->label('Title'),
        ];

        if ($addAfter) {
            $params = [$addAfter, ...$params];
        }

        $form->{$addAfter ? 'addAfter' : 'add'}(...$params);

        if ($hasLimitation) {
            $form->addAfter(
                'title',
                'limit',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(__('Number display posts'))
                    ->defaultValue(6)
            );
        }

        return $form;
    }
}
