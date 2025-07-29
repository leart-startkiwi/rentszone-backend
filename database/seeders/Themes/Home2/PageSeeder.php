<?php

namespace Database\Seeders\Themes\Home2;

use Botble\Blog\Models\Category;
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
                    'style' => 'style-1',
                ]) .
                Shortcode::generateShortcode('car-types', [
                    'style' => 'style-2',
                    'title' => 'Browse by Type',
                    'sub_title' => 'Find the perfect ride for any occasion',
                    'car_types' => CarType::query()->wherePublished()->limit(6)->pluck('id')->implode(','),
                    'redirect_url' => '/cars',
                    'button_label' => 'View More',
                    'button_url' => '/cars',
                ]) .
                Shortcode::generateShortcode('cars', [
                    'style' => 'style-popular',
                    'title' => 'Popular Vehicles',
                    'subtitle' => 'Favorite vehicles based on customer reviews',
                    'filter_types' => implode(',', ['category', 'fuel_type', 'order', 'price_range', ]),
                    'limit' => 8,
                    'button_label' => 'View More',
                    'button_url' => '/Cars',
                ]) .
                Shortcode::generateShortcode('rental-invitations', [
                    'title' => 'Best Car Rental System',
                    'description' => 'Receive a Competitive Offer <br> Sell Your Car to Us Today.',
                    'middle_image' => $this->filePath('icons/car-center.png'),
                    'background_image' => $this->filePath('backgrounds/background.jpg'),
                    ...$this->genTabData(data: [
                        [
                            'icon' => $this->filePath('icons/icon-1.png'),
                            'title' => 'Looking for a rental car?',
                            'subtitle' => 'Find your perfect rental car for any journey, from road trips to business travel.',
                            'button_url' => '/cars',
                            'button_name' => 'Get Started Now',
                        ],
                        [
                            'icon' => $this->filePath('icons/icon-2.png'),
                            'title' => 'Looking for a rental car?',
                            'subtitle' => 'Find your perfect rental car for any journey, from road trips to business travel.',
                            'button_url' => '/cars',
                            'button_name' => 'Get Started Now',
                        ],
                    ]),
                ]) .
                Shortcode::generateShortcode('simple-slider', [
                    'key' => 'home-slider-02',
                    'style' => 'style-2',
                ]) .
                Shortcode::generateShortcode('site-statistics', [
                    'style' => 'style-1',
                    ...$this->genTabData(data: [
                        [
                            'title' => 'Global <br> Branches',
                            'data' => '45',
                            'unit' => '+',
                        ],
                        [
                            'title' => 'Destinations <br> Collaboration',
                            'data' => '29',
                            'unit' => 'K',
                        ],
                        [
                            'title' => 'Years <br> Experience',
                            'data' => '20',
                            'unit' => '+',
                        ],
                        [
                            'title' => 'Happy <br> Customers',
                            'data' => '168',
                            'unit' => 'K',
                        ],
                        [
                            'title' => 'User <br> Account',
                            'data' => '15',
                            'unit' => 'M',
                        ],
                    ]),
                    'background_color' => 'rgb(227, 240, 255)',
                ]) .
                Shortcode::generateShortcode('cars', [
                    'style' => 'style-feature',
                    'title' => 'Featured Listings',
                    'subtitle' => 'Find the perfect ride for any occasion',
                    'limit' => 8,
                    'button_label' => 'View More',
                    'button_url' => '/cars',
                ]) .
                Shortcode::generateShortcode('brands', [
                    'style' => 'style-2',
                    'title' => 'Premium Brands',
                    'subtitle' => 'Unveil the Finest Selection of High-End Vehicles',
                    'brand_ids' => '2,8,9,10,11,12,13,14',
                    'button_label' => 'Show All Brands',
                    'button_url' => '/brands',
                ]) .
                Shortcode::generateShortcode('team', [
                    'title' => 'Meet Our Agents',
                    'subtitle' => 'Awesome Teams',
                    'team_ids' => '1,2,3,4,5,6,7,8',
                ]) .
                Shortcode::generateShortcode('car-loan-form', [
                    'style' => 'style-2',
                    'title' => 'Want to Calculate Your Car Payment?',
                    'description' => 'Match with up to 4 lenders to get the lowest rate available with no markups, no fees, and no obligations.',
                    'form_url' => '/contact',
                    'form_title' => 'Car Loan Calculator',
                    'form_description' => 'Estimate your monthly auto loan payments with this calculator.',
                    'form_button_label' => 'Apply for a loan',
                    'background_image' => $this->filePath('backgrounds/car-loan-form-bg-1.jpg'),
                    'enable_lazy_loading' => false,
                ]) .
                Shortcode::generateShortcode('testimonials', [
                    'title' => 'What they say about us?',
                    'subtitle' => 'Testimonials',
                    'testimonial_ids' => '1,2,3,4',
                    'style' => 'style-2',
                    'enable_lazy_loading' => false,
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
                Shortcode::generateShortcode('blog-posts', [
                    'style' => 'style-2',
                    'title' => 'Inside & Trending',
                    'subtitle' => 'The latest news and events',
                    'category_ids' => Category::query()->wherePublished()->pluck('id')->implode(','),
                    'button_label' => 'Keep Reading',
                    'limit' => 10,
                ]) .
                Shortcode::generateShortcode('install-apps', [
                    'style' => 'style-2',
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
                    'background_color' => '#f6f3fc',
                ])
            ),
        ]);
    }
}
