<?php

namespace Theme\Carento\Http\Controllers;

use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\CarRentals\Models\Car;
use Botble\CarRentals\Models\Currency;
use Botble\Theme\Facades\Theme;
use Botble\Theme\Http\Controllers\PublicController;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CarentoController extends PublicController
{
    public function getIndex()
    {
        return parent::getIndex();
    }

    public function getView(?string $key = null, string $prefix = '')
    {
        return parent::getView($key);
    }

    public function getSiteMapIndex(string $key = null, string $extension = 'xml')
    {
        return parent::getSiteMapIndex();
    }

    public function calculateLoanCar(Request $request)
    {
        $request->validate([
            'vehicle_price' => 'required|numeric',
            'down_payment' => 'required|numeric|lte:vehicle_price',
            'loan_term' => 'required|numeric',
            'annual_interest_rate' => 'required|numeric|min:0.01',
            'currency' => 'required',
        ]);

        $price = $request->input('vehicle_price');
        $downPayment = $request->input('down_payment');
        $loanTerm = $request->input('loan_term');
        $interestRate = $request->input('annual_interest_rate');
        $currency = $request->input('currency');

        $currency = Currency::query()->where('title', $currency)->firstOrFail();

        $loanAmount = $price - $downPayment;
        $monthlyInterestRate = $interestRate / 100 / 12;
        $monthlyPayment = $loanAmount * $monthlyInterestRate / (1 - (1 + $monthlyInterestRate) ** -$loanTerm);

        return response()->json([
            'monthly_payment' => format_price($monthlyPayment, $currency),
            'loan_amount' => format_price($loanAmount, $currency),
            'down_payment' => format_price($downPayment, $currency),
            'total_payment' => format_price($monthlyPayment * $loanTerm, $currency),
        ]);
    }

    public function ajaxSearchPopularVehicles(
        Request $request,
        BaseHttpResponse $response
    ) {
        $limit = $request->query('limit', 9);
        $category = $request->query('category');
        $fuelType = $request->query('fuel_type');
        $order = $request->query('order', 'asc');
        $priceRange = $request->query('price_range');

        $cars = Car::query()
            ->with(['pickupAddress', 'transmission', 'fuel'])
            ->withCount('reviews')
            ->withSum('reviews', 'star')
            ->when(empty($category) === false, function (Builder $query) use ($category) {
                $query->where('vehicle_type_id', $category);
            })
            ->when(empty($fuelType) === false, function (Builder $query) use ($fuelType) {
                $query->where('fuel_type_id', $fuelType);
            })
            ->when(empty($order) === false, function (Builder $query) use ($order) {
                $query->orderBy('id', $order);
            })
            ->when(empty($priceRange) === false, function (Builder $query) use ($priceRange) {
                [$min, $max] = explode('_', $priceRange);
                $query->where('rental_rate', '>=', $min);
                $query->where('rental_rate', '<=', $max);
            })
            ->limit($limit)
            ->get();

        return $response
            ->setData(Theme::partial('shortcodes.cars.popular-main', compact('cars')))
            ->setAdditional(['total' => $cars->count()]);
    }
}
