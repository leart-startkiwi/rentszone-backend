<?php

namespace Botble\CarRentals\Http\Controllers\Cars;

use Botble\Base\Facades\Assets;
use Botble\Base\Forms\FormAbstract;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Requests\UpdateTreeCategoryRequest;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Supports\RepositoryHelper;
use Botble\CarRentals\Forms\CarCategoryForm;
use Botble\CarRentals\Http\Requests\CarCategoryRequest;
use Botble\CarRentals\Models\CarCategory;
use Illuminate\Http\Request;

class CarCategoryController extends BaseController
{
    public function __construct()
    {
        $this->breadcrumb()
            ->add(trans('plugins/car-rentals::car-rentals.attribute.name'));
    }

    public function index(Request $request)
    {
        $this->pageTitle(trans('plugins/car-rentals::car-rentals.attribute.category.name'));

        $categories = CarCategory::query()
            ->orderByDesc('is_default')
            ->oldest('order')->oldest()
            ->with('slugable')
            ->withCount('cars');

        $categories = RepositoryHelper::applyBeforeExecuteQuery($categories, new CarCategory())->get();

        if ($request->ajax()) {
            $data = view('core/base::forms.partials.tree-categories', $this->getOptions(compact('categories')))
                ->render();

            return $this
                ->httpResponse()
                ->setData($data);
        }

        Assets::addStylesDirectly('vendor/core/core/base/css/tree-category.css')
            ->addScriptsDirectly('vendor/core/core/base/js/tree-category.js');

        $form = CarCategoryForm::create(['template' => 'core/base::forms.form-tree-category']);
        $form = $this->setFormOptions($form, null, compact('categories'));

        return $form->renderForm();
    }

    public function create(Request $request)
    {
        $this->breadcrumb()
            ->add(
                trans('plugins/car-rentals::car-rentals.attribute.category.name'),
                route('car-rentals.car-categories.index')
            );

        $this->pageTitle(trans('plugins/car-rentals::car-rentals.attribute.category.create'));

        if ($request->ajax()) {
            return $this
                ->httpResponse()
                ->setData($this->getForm());
        }

        return CarCategoryForm::create()->renderForm();
    }

    public function store(CarCategoryRequest $request): BaseHttpResponse
    {
        if ($request->input('is_default')) {
            CarCategory::query()->where('id', '>', 0)->update(['is_default' => 0]);
        }

        $form = CarCategoryForm::create();
        $form->save($request->validated());

        $response = $this->httpResponse();

        /**
         * @var CarCategory $carCategory
         */
        $carCategory = $form->getModel();

        if ($request->ajax()) {
            if ($response->isSaving()) {
                $form = $this->getForm();
            } else {
                $form = $this->getForm($carCategory);
            }

            $response->setData([
                'model' => $carCategory,
                'form' => $form,
            ]);
        }

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('car-rentals.car-categories.index'))
            ->setNextUrl(route('car-rentals.car-categories.edit', $carCategory->getKey()))
            ->withCreatedSuccessMessage();
    }

    public function edit(CarCategory $carCategory, Request $request)
    {
        if ($request->ajax()) {
            return $this
                ->httpResponse()
                ->setData($this->getForm($carCategory));
        }

        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $carCategory->name]));

        return CarCategoryForm::createFromModel($carCategory)->renderForm();
    }

    public function update(CarCategory $carCategory, CarCategoryRequest $request): BaseHttpResponse
    {
        if ($request->input('is_default')) {
            CarCategory::query()->where('id', '!=', $carCategory->getKey())->update(['is_default' => 0]);
        }

        CarCategoryForm::createFromModel($carCategory)
            ->setRequest($request)
            ->save();

        $response = $this->httpResponse();

        if ($request->ajax()) {
            if ($response->isSaving()) {
                $form = $this->getForm();
            } else {
                $form = $this->getForm($carCategory);
            }

            $response->setData([
                'model' => $carCategory,
                'form' => $form,
            ]);
        }

        return $response
            ->setPreviousUrl(route('car-rentals.car-categories.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(CarCategory $carCategory): DeleteResourceAction
    {
        return DeleteResourceAction::make($carCategory);
    }

    public function updateTree(UpdateTreeCategoryRequest $request): BaseHttpResponse
    {
        CarCategory::updateTree($request->validated('data'));

        return $this
            ->httpResponse()
            ->withUpdatedSuccessMessage();
    }

    protected function setFormOptions(FormAbstract $form, ?CarCategory $model = null, array $options = []): FormAbstract
    {
        if (! $model) {
            $form->setUrl(route('car-rentals.car-categories.create'));
        }

        $form->setFormOptions($this->getOptions($options));

        return $form;
    }

    protected function getOptions(array $options = []): array
    {
        return array_merge([
            'canCreate' => true,
            'canEdit' => true,
            'canDelete' => true,
            'createRoute' => 'car-rentals.car-categories.create',
            'editRoute' => 'car-rentals.car-categories.edit',
            'deleteRoute' => 'car-rentals.car-categories.destroy',
            'updateTreeRoute' => 'car-rentals.car-categories.update-tree',
        ], $options);
    }

    protected function getForm(?CarCategory $model = null): string
    {
        $options = ['template' => 'core/base::forms.form-no-wrap'];

        if ($model) {
            $options['model'] = $model;
        }

        $form = CarCategoryForm::create($options);

        $form = $this->setFormOptions($form, $model);

        return $form->renderForm();
    }
}
