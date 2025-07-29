<?php

namespace Botble\CarRentals\Forms\Fronts\Auth;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Theme\Facades\Theme;
use Botble\Theme\FormFront;

abstract class AuthForm extends FormFront
{
    public function setup(): void
    {
        Theme::asset()->add('auth-css', 'vendor/core/plugins/car-rentals/css/front-auth.css');

        $this
            ->contentOnly()
            ->setFormOption('class', 'auth-form')
            ->template('plugins/car-rentals::forms.auth');
    }

    public function submitButton(string $label, string $icon = null, string $iconPosition = 'append'): static
    {
        $iconHtml = $icon ? BaseHelper::renderIcon($icon) : '';

        return $this
            ->add('openButtonWrap', HtmlField::class, [
                'html' => '<div class="d-grid">',
            ])
            ->add('submit', 'submit', [
                'label' =>
                    ($icon && $iconPosition === 'prepend' ? $iconHtml : '')
                    . $label
                    . ($icon && $iconPosition === 'append' ? $iconHtml : ''),
                'attr' => [
                    'class' => 'btn btn-primary btn-auth-submit',
                ],
            ])
            ->add('closeButtonWrap', HtmlField::class, [
                'html' => '</div>',
            ]);
    }

    public function banner(string $banner): static
    {
        return $this->setFormOption('banner', $banner);
    }

    public function icon(string $icon): static
    {
        return $this->setFormOption('icon', $icon);
    }

    public function heading(string $heading): static
    {
        return $this->setFormOption('heading', $heading);
    }

    public function description(string $description): static
    {
        return $this->setFormOption('description', $description);
    }
}
