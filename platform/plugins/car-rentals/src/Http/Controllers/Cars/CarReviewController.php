<?php

namespace Botble\CarRentals\Http\Controllers\Cars;

use Botble\Base\Facades\Assets;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\CarRentals\Forms\CarReviewForm;
use Botble\CarRentals\Models\CarReview;
use Botble\CarRentals\Tables\CarReviewTable;

class CarReviewController extends BaseController
{
    public function __construct()
    {
        $this->breadcrumb()
            ->add(trans('plugins/car-rentals::car-rentals.name'))
            ->add(trans('plugins/car-rentals::car-rentals.review.name'), route('car-rentals.reviews.index'));
    }

    public function index(CarReviewTable $table)
    {
        $this->pageTitle(trans('plugins/car-rentals::car-rentals.review.name'));

        Assets::addStylesDirectly('vendor/core/plugins/car-rentals/css/review.css');

        return $table->renderTable();
    }

    public function edit(CarReview $review)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $review->getKey()]));

        Assets::addStylesDirectly('vendor/core/plugins/car-rentals/css/review.css');

        return CarReviewForm::createFromModel($review)->renderForm();
    }

    public function update(CarReview $review)
    {
        CarReviewForm::createFromModel($review)->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('car-rentals.reviews.index'))
            ->setNextUrl(route('car-rentals.reviews.edit', $review->getKey()))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(CarReview $review)
    {
        return DeleteResourceAction::make($review);
    }
}
