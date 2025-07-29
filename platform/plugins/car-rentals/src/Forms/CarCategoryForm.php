<?php

namespace Botble\CarRentals\Forms;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\IsDefaultFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\CoreIconField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\CarRentals\Http\Requests\CarCategoryRequest;
use Botble\CarRentals\Models\CarCategory;
use Botble\CarRentals\Services\GetCategoriesService;

class CarCategoryForm extends FormAbstract
{
    public function setup(): void
    {
        /** @var GetCategoriesService $service */
        $service = app(GetCategoriesService::class);
        $list = $service->execute(['conditions' => []]);

        $categories = [];
        foreach ($list as $category) {
            if (
                $this->getModel()
                && ($this->model->id === $category->id || $this->model->id === $category->parent_id)
            ) {
                continue;
            }

            $categories[$category->id] = $category->indent_text . ' ' . $category->name;
        }

        $categories = [0 => trans('plugins/car-rentals::car-rentals.attribute.category.forms.none')] + $categories;

        $maxOrder = CarCategory::query()
            ->whereIn('parent_id', [0, null])
            ->latest('order')
            ->value('order');

        $this
            ->model(CarCategory::class)
            ->setValidatorClass(CarCategoryRequest::class)
            ->add('order', 'hidden', [
                'value' => $this->getModel()->exists ? $this->getModel()->order : $maxOrder + 1,
            ])
            ->add(
                'name',
                TextField::class,
                NameFieldOption::make()
                    ->required()
                    ->maxLength(120)
            )
            ->add(
                'parent_id',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('core/base::forms.parent'))
                    ->choices($categories)
                    ->searchable()
            )
            ->add('description', TextareaField::class, DescriptionFieldOption::make())
            ->add('is_default', OnOffField::class, IsDefaultFieldOption::make())
            ->add(
                'icon',
                $this->getFormHelper()->hasCustomField('themeIcon') ? 'themeIcon' : CoreIconField::class,
                TextFieldOption::make()
                    ->label(trans('core/base::forms.icon'))
                    ->placeholder(trans('core/base::forms.icon_placeholder'))
                    ->addAttribute('data-allow-clear', 'true')
                    ->maxLength(120)
            )
            ->add(
                'is_featured',
                OnOffField::class,
                OnOffFieldOption::make()
                    ->label(trans('core/base::forms.is_featured'))
                    ->defaultValue(false)
            )
            ->add('status', SelectField::class, StatusFieldOption::make()->choices(BaseStatusEnum::labels()))
            ->setBreakFieldPoint('status');
    }
}
