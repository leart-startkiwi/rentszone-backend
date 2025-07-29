<?php

namespace Database\Seeders\Themes\Home3;

use Botble\Blog\Models\Category;
use Botble\CarRentals\Models\CarMake;
use Botble\CarRentals\Models\CarType;
use Botble\Page\Models\Page;
use Botble\Shortcode\Facades\Shortcode;

class PageSeeder extends \Database\Seeders\Themes\Main\PageSeeder
{
    public function run(): void
    {
        parent::run();

        $homepage = Page::query()->where('name', 'Homepage')->firstOrFail();

        $homepage->update([
            'content' => htmlentities(
                Shortcode::generateShortcode('simple-slider', [
                    'key' => 'home-slider',
                    'style' => 'style-3',
                ]) .
                Shortcode::generateShortcode('car-advance-search', [
                    'button_search_name' => 'Find a Vehicle',
                    'link_need_help' => '/cars',
                    'top' => -124,
                    'bottom' => 0,
                    'left' => 0,
                    'right' => 0,
                    'url' => '/cars',
                    'tabs' => 'all,new_car,used_car',
                ]) .
                Shortcode::generateShortcode('cars', [
                    'style' => 'style-popular',
                    'title' => 'Popular Vehicles',
                    'subtitle' => 'Favorite vehicles based on customer reviews',
                    'filter_types' => implode(',', ['category', 'fuel_type', 'order', 'price_range', ]),
                    'limit' => 8,
                    'button_label' => 'View More',
                    'button_url' => '/cars',
                ]) .
                Shortcode::generateShortcode('brands', [
                    'title' => 'Premium Brands',
                    'subtitle' => 'Unveil the Finest Selection of High-End Vehicles',
                    'brand_ids' => CarMake::query()->wherePublished()->pluck('id')->implode(','),
                    'button_label' => 'Show All Brands',
                    'button_url' => '/brands',
                ]) .
                Shortcode::generateShortcode('why-us', [
                    'sub_title' => 'HOW IT WORKS',
                    'title' => 'Presenting Your New Go-To Car <br> Rental Experience',
                    ...$this->genTabData(data: [
                        [
                            'card_image' => $this->filePath('icons/car-location.png'),
                            'card_title' => 'Choose a Location',
                            'card_content' => 'Select the ideal destination to begin your journey with ease',
                        ],
                        [
                            'card_image' => $this->filePath('icons/car-selected.png'),
                            'card_title' => 'Choose Your Vehicle',
                            'card_content' => 'Browse our fleet and find the perfect car for your needs',
                        ],
                        [
                            'card_image' => $this->filePath('icons/car.png'),
                            'card_title' => 'Verification',
                            'card_content' => 'Review your information and confirm your booking',
                        ],
                        [
                            'card_image' => $this->filePath('icons/car-key.png'),
                            'card_title' => 'Begin Your Journey',
                            'card_content' => 'Start your adventure with confidence and ease',
                        ],
                    ]),
                ]) .
                Shortcode::generateShortcode('simple-banners', [
                    ...$this->genTabData(data: [
                        [
                            'title' => 'Looking for a rental car?',
                            'subtitle' => "Discover your ideal rental car for every adventure, <br>whether it's a road trip or business travel",
                            'image' => $this->filePath('cars/img-1.png'),
                            'button_url' => '/cars',
                            'button_name' => 'Get Started Now',
                            'button_color' => '#70f46d',
                            'background_color' => '#9dd3fb',
                        ],
                        [
                            'title' => 'Looking for a rental car?',
                            'subtitle' => "Maximize your vehicle's potential: seamlessly <br> rent or sell with confidence",
                            'image' => $this->filePath('cars/img-2.png'),
                            'button_url' => '/cars',
                            'button_name' => 'Get Started Now',
                            'button_color' => '#ffffff',
                            'background_color' => '#ffec88',
                        ],
                    ]),
                ]) .
                Shortcode::generateShortcode('featured-block', [
                    'style' => 'style-2',
                    'title' => 'Sell your car at a fair price.<br /> Get started with us today.',
                    'subtitle' => 'Best Car Rental System',
                    'description' => 'We are committed to delivering exceptional service, competitive pricing, and a diverse selection of options for our customers',
                    'button_label' => 'Get Started Now',
                    'button_url' => '/contact',
                    'quantity' => '3',
                    'content_1' => 'Explore a wide range of flexible rental options to suit your needs',
                    'content_2' => 'Comprehensive insurance coverage for complete peace of mind',
                    'content_3' => ' 24/7 customer support for assistance anytime, anywhere',
                    'image_1' => $this->filePath('general/img-2-1.png'),
                    'image_2' => $this->filePath('general/img-2-2.png'),
                    'image_3' => $this->filePath('general/img-2-3.png'),
                    'image_4' => $this->filePath('general/img-2-4.png'),
                    'enable_lazy_loading' => 'no',
                ]) .
                Shortcode::generateShortcode('site-statistics', [
                    'quantity' => '5',
                    'title_1' => 'Global <br> Branches',
                    'data_1' => '45',
                    'unit_1' => '+',
                    'title_2' => 'Destinations  <br> Collaboration',
                    'data_2' => '29',
                    'unit_2' => 'K',
                    'title_3' => 'Years <br> Experience',
                    'data_3' => '20',
                    'unit_3' => '+',
                    'title_4' => 'Happy <br> Customers',
                    'data_4' => '168',
                    'unit_4' => 'K',
                    'title_5' => 'User  <br> Account',
                    'data_5' => '15',
                    'unit_5' => 'M',
                ]) .
                Shortcode::generateShortcode('car-types', [
                    'title' => 'Browse by Type',
                    'sub_title' => 'Find the perfect ride for any occasion',
                    'car_types' => CarType::query()->wherePublished()->pluck('id')->implode(','),
                    'redirect_url' => '/cars',
                    'button_label' => 'View More',
                    'button_url' => '/cars',
                ]) .
                Shortcode::generateShortcode('car-loan-form', [
                    'style' => 'style-3',
                    'form_url' => '/contact',
                    'form_title' => 'Car Loan Calculator',
                    'form_description' => 'Estimate your monthly auto loan payments with this calculator.',
                    'form_button_label' => 'Apply for a loan',
                    'background_image' => $this->filePath('backgrounds/car-loan-form-bg-2.jpg'),
                    'enable_lazy_loading' => false,
                ]) .
                Shortcode::generateShortcode('cars', [
                    'style' => 'style-feature',
                    'title' => 'Featured Listings',
                    'subtitle' => 'Find the perfect ride for any occasion',
                    'limit' => 4,
                    'button_label' => 'View More',
                    'button_url' => '/cars',
                ]) .
                Shortcode::generateShortcode('testimonials', [
                    'title' => 'What our customers say',
                    'subtitle' => 'Testimonials',
                    'testimonial_ids' => '1,2,3,4',
                    'style' => 'style-3',
                    'enable_lazy_loading' => false,
                ]) .
                Shortcode::generateShortcode('cars-by-locations', [
                    'title' => 'Available Car Rentals',
                    'main_content' => 'Choose the location that suits your journey and start exploring today',
                    'city_ids' => '1,2,3,5,6',
                    'button_label' => 'View More',
                    'button_url' => '/cars',
                    'redirect_url' => '/cars',
                ]) .
                Shortcode::generateShortcode('simple-slider', [
                    'key' => 'home-slider-02',
                    'style' => 'style-2',
                ]) .
                Shortcode::generateShortcode('team', [
                    'title' => 'Meet Our Agents',
                    'subtitle' => 'Awesome Teams',
                    'team_ids' => '1,2,3,5',
                ]) .
                Shortcode::generateShortcode('blog-posts', [
                    'title' => 'Upcoming Cars & Events',
                    'subtitle' => 'Stay ahead with the latest car releases and upcoming events',
                    'button_label' => 'Keep Reading',
                    'category_ids' => Category::query()->wherePublished()->pluck('id')->implode(','),
                    'limit' => 10,
                ]) .
                Shortcode::generateShortcode('install-apps', [
                    'style' => 'style-3',
                    'title' => 'Carento App is Available',
                    'description' => 'Install App',
                    'apps_description' => 'Manage all your car rentals on the go with the Carento app',
                    'android_app_url' => '/contact',
                    'android_app_image' => $this->filePath('general/googleplay.png'),
                    'ios_app_url' => '/contact',
                    'ios_app_image' => $this->filePath('general/appstore.png'),
                    'decor_image' => $this->filePath('general/phone.png'),
                    'button_label' => 'Download Apps',
                    'button_url' => '/contact',
                    'background_color' => '#f2f4f6',
                ]),
            ),
        ]);
    }
}
