<?php

namespace Database\Seeders\Themes\Main;

use Botble\Base\Supports\BaseSeeder;
use Botble\CarRentals\Models\CarMake;
use Botble\CarRentals\Models\CarType;
use Botble\Page\Database\Traits\HasPageSeeder;
use Botble\Shortcode\Facades\Shortcode;

class PageSeeder extends BaseSeeder
{
    use HasPageSeeder;

    public function run(): void
    {
        $this->truncatePages();

        $this->uploadFiles('cars');
        $this->uploadFiles('icons');
        $this->uploadFiles('backgrounds');

        $pages = [
            [
                'name' => 'Homepage',
                'content' => htmlentities(
                    Shortcode::generateShortcode('hero-banners', [
                        'title' => 'Looking for a vehicle? <br class=“d-none d-lg-block” />You’re in the perfect spot.',
                        'subtitle' => 'Find Your Perfect Car',
                        'background_image' => $this->filePath('backgrounds/hero-banner.jpg'),
                        ...$this->genTabData(data: [
                            [
                                'content' => 'High quality at a low cost.',
                            ],
                            [
                                'content' => 'Premium services',
                            ],
                            [
                                'content' => '24/7 roadside support.',
                            ],
                        ]),
                    ]) .
                    Shortcode::generateShortcode('car-advance-search', [
                        'button_search_name' => 'Find a Vehicle',
                        'link_need_help' => '/faqs',
                        'top' => -124,
                        'bottom' => 0,
                        'left' => 0,
                        'right' => 0,
                        'url' => '/cars',
                        'background_color' => 'rgb(242, 244, 246)',
                        'tabs' => 'all,new_car,used_car',
                    ]) .
                    Shortcode::generateShortcode('brands', [
                        'style' => 'style-1',
                        'title' => 'Premium Brands',
                        'subtitle' => 'Unveil the Finest Selection of High-End Vehicles',
                        'brand_ids' => CarMake::query()->wherePublished()->pluck('id')->implode(','),
                        'button_label' => 'Show All Brands',
                        'button_url' => '/brands',
                    ]) .
                    Shortcode::generateShortcode('cars', [
                        'style' => 'style-latest',
                        'title' => 'Most View Vehicles',
                        'subtitle' => "The world's leading car brands",
                        'number_rows' => 2,
                        'limit' => 12,
                        'button_label' => 'View More',
                        'button_url' => '/cars',
                    ]) .
                    Shortcode::generateShortcode('intro-video', [
                        'title' => 'Receive a Competitive Offer Sell Your Car to Us Today.',
                        'description' => 'We are committed to delivering exceptional service, competitive pricing, and a diverse selection of options for our customers.',
                        'subtitle' => 'Best Car Rental System',
                        'youtube_video_url' => 'https://www.youtube.com/watch?v=ldusxyoq0Y8',
                        'image' => $this->filePath('general/1.jpg'),
                        ...$this->genTabData(data: [
                            [
                                'content' => 'Expert Certified Mechanics',
                            ],
                            [
                                'content' => 'First Class Services',
                            ],
                            [
                                'content' => 'Get Reasonable Price',
                            ],
                            [
                                'content' => '24/7 road assistance',
                            ],
                            [
                                'content' => 'Genuine Spares Parts',
                            ],
                            [
                                'content' => 'Free Pick-Up & Drop-Offs',
                            ],
                        ]),
                    ]) .
                    Shortcode::generateShortcode('car-types', [
                        'title' => 'Browse by Type',
                        'sub_title' => 'Find the perfect ride for any occasion',
                        'car_types' => CarType::query()->wherePublished()->pluck('id')->implode(','),
                        'redirect_url' => '/cars',
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
                    Shortcode::generateShortcode('car-loan-form', [
                        'style' => 'style-1',
                        'title' => 'Want to Calculate Your Car Payment?',
                        'description' => 'Match with up to 4 lenders to get the lowest rate available with no markups, no fees, and no obligations.',
                        'form_url' => '/contact',
                        'form_title' => 'Car Loan Calculator',
                        'form_description' => 'Estimate your monthly auto loan payments with this calculator.',
                        'form_button_label' => 'Apply for a loan',
                        'background_image' => $this->filePath('backgrounds/car-loan-form-bg.jpg'),
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
                    Shortcode::generateShortcode('featured-block', [
                        'title' => 'Get a great deal for your vehicle sell to us now',
                        'subtitle' => 'Trusted Expertise',
                        'description' => 'Get the best value for your vehicle with our transparent and straightforward selling process',
                        'button_label' => 'Get Started Now',
                        'button_url' => '/cars',
                        'image_1' => $this->filePath('general/img-1.png'),
                        'image_2' => $this->filePath('general/img-2.png'),
                        'image_3' => $this->filePath('general/img-3.png'),
                        'image_4' => $this->filePath('general/img-4.png'),
                        'image_5' => $this->filePath('general/img-5.png'),
                        ...$this->genTabData(data: [
                            [
                                'content' => 'Experienced Professionals You Can Trust',
                            ],
                            [
                                'content' => 'Clear and Transparent Pricing, No Hidden Fees',
                            ],
                            [
                                'content' => 'Genuine Spares Parts',
                            ],
                        ]),
                    ]) .
                    Shortcode::generateShortcode('cars-by-locations', [
                        'title' => 'Available Car Rentals',
                        'main_content' => 'Choose the location that suits your journey and start exploring today',
                        'city_ids' => '1,2,3,5,6',
                        'button_label' => 'View More',
                        'button_url' => '/cars',
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
                    Shortcode::generateShortcode('testimonials', [
                        'title' => 'What they say about us?',
                        'subtitle' => 'Testimonials',
                        'testimonial_ids' => '1,2,3,4',
                    ]) .
                    Shortcode::generateShortcode('blog-posts', [
                        'style' => 'style-3',
                        'title' => 'Car Reviews',
                        'subtitle' => 'Expert insights and honest evaluations to help you choose the perfect car',
                        'link_label' => 'View More',
                        'link_url' => '/blog',
                        'category_ids' => '1,2,3,4,5,6',
                        'limit' => 4,
                    ]) .
                    Shortcode::generateShortcode('blog-posts', [
                        'style' => 'style-1',
                        'title' => 'Upcoming Cars & Events',
                        'subtitle' => 'Stay ahead with the latest car releases and upcoming events',
                        'button_label' => 'Keep Reading',
                        'category_ids' => '1,2,3,4,5,6',
                        'limit' => 10,
                    ]) .
                    Shortcode::generateShortcode('install-apps', [
                        'title' => 'Carento App Is Available',
                        'description' => 'Install App',
                        'apps_description' => 'Manage all your car rentals on the go with the Carento app',
                        'android_app_url' => '/contact',
                        'android_app_image' => $this->filePath('general/googleplay.png'),
                        'ios_app_url' => '/contact',
                        'ios_app_image' => $this->filePath('general/appstore.png'),
                        'decor_image' => $this->filePath('general/truck.png'),
                        'background_image' => $this->filePath('general/background.png'),
                    ]),
                ),
                'template' => 'homepage',
            ],
            [
                'name' => 'Blog',
                'content' => '',
                'template' => 'blog-with-sidebar',
                'metadata' => [
                    'breadcrumb_simple' => true,
                ],
            ],
            [
                'name' => 'Contact',
                'content' => Shortcode::generateShortcode('branch-locations', [
                        'title' => 'Our agents worldwide',
                        'quantity' => '4',
                        'name_1' => 'New York',
                        'icon_image_1' => $this->filePath('icons/sedan-car-model.png'),
                        'phone_1' => '+1 212 555 0146',
                        'email_1' => 'newyork@carento.com',
                        'address_1' => '750 7th Avenue, Manhattan, New York, NY 10019, USA',

                        'name_2' => 'Tokyo',
                        'icon_image_2' => $this->filePath('icons/car-city-model.png'),
                        'phone_2' => '+81 3 3456 7890',
                        'email_2' => 'tokyo@carento.com',
                        'address_2' => '2-11-3 Meguro, Meguro City, Tokyo 153-0063, Japan',

                        'name_3' => 'Paris',
                        'icon_image_3' => $this->filePath('icons/jeep.png'),
                        'phone_3' => '+33 1 42 68 53 00',
                        'email_3' => 'paris@carento.com',
                        'address_3' => '22 Rue de la Paix, 75002 Paris, France',

                        'name_4' => 'Sydney',
                        'icon_image_4' => $this->filePath('icons/pick-up.png'),
                        'phone_4' => '+61 2 9255 6000',
                        'email_4' => 'sydney@carento.com',
                        'address_4' => '88 George Street, The Rocks, Sydney NSW 2000, Australia',
                    ]) .
                    Shortcode::generateShortcode('contact-form', [
                        'display_fields' => 'phone,email,subject,address',
                        'mandatory_fields' => 'email',
                        'title' => 'Get in Touch',
                        'show_map' => '0,1',
                        'map_title' => 'Our location',
                        'map_address' => '12560 Rental Rd, Memphis, TN 38118, United States',
                    ]),
                'metadata' => [
                    'breadcrumb_background_image' => $this->filePath('backgrounds/contact-bg.jpg'),
                    'breadcrumb_text_color' => '#ffffff',
                ],
            ],
            [
                'name' => 'Services',
                'content' =>
                    Shortcode::generateShortcode('car-services', [
                        'title' => 'Comprehensive Car Rental Services to Meet All Your Needs',
                        'description' => 'From daily rentals to long-term solutions, we offer a comprehensive range of vehicles and services to suit every need and budget.',
                        'limit' => '10',
                    ]) .
                    Shortcode::generateShortcode('promotion-block', [
                        'title' => 'Best Car Rent Deals',
                        'subtitle' => 'Save 15% or more when you book and ride before 1 April 2025',
                        'button_label' => 'Find Early 2025 Deals',
                        'button_url' => '/cars',
                        'background_image' => $this->filePath('backgrounds/promotion-block-bg.jpg'),
                    ]) .
                    Shortcode::generateShortcode('testimonials', [
                        'title' => 'What they say about us?',
                        'subtitle' => 'Testimonials',
                        'testimonial_ids' => '1,2,3,4',
                        'style' => 'style-2',
                        'enable_lazy_loading' => false,
                    ]) .
                    Shortcode::generateShortcode('intro-video', [
                        'title' => 'Receive a Competitive Offer Sell Your Car to Us Today.',
                        'description' => 'We are committed to delivering exceptional service, competitive pricing, and a diverse selection of options for our customers.',
                        'subtitle' => 'Best Car Rental System',
                        'image' => $this->filePath('general/1.jpg'),
                        ...$this->genTabData(data: [
                            [
                                'content' => 'Expert Certified Mechanics',
                            ],
                            [
                                'content' => 'First Class Services',
                            ],
                            [
                                'content' => 'Get Reasonable Price',
                            ],
                            [
                                'content' => '24/7 road assistance',
                            ],
                            [
                                'content' => 'Genuine Spares Parts',
                            ],
                            [
                                'content' => 'Free Pick-Up & Drop-Offs',
                            ],
                        ]),
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
                    Shortcode::generateShortcode('blog-posts', [
                        'style' => 'style-1',
                        'title' => 'Upcoming Cars & Events',
                        'subtitle' => 'Stay ahead with the latest car releases and upcoming events',
                        'button_label' => 'Keep Reading',
                        'category_ids' => '1,2,3,4,5',
                        'limit' => 10,
                    ]),
                'template' => 'full-width',
                'metadata' => [
                    'breadcrumb_background_image' => $this->filePath('backgrounds/service-bg.jpg'),
                    'breadcrumb_text_color' => '#ffffff',
                ],
            ],
            [
                'name' => 'Pricing',
                'content' =>
                    Shortcode::generateShortcode('pricing', [
                        'title' => 'Membership Plans',
                        'button_label_monthly' => 'Monthly Price',
                        'button_label_yearly' => 'Annual Price',
                        'quantity' => '4',
                        'name_1' => 'Basic',
                        'description_1' => 'For most businesses that want to optimize web queries',
                        'monthly_price_1' => '19',
                        'yearly_price_1' => '348',
                        'features_1' => '+ Access to standard vehicles \n + 24/7 customer support \n + Basic insurance coverage \n + Online booking \n + Standard roadside assistance \n+ One free vehicle per month',
                        'button_label_1' => 'Get Started Now',
                        'button_url_1' => '/',
                        'name_2' => 'Standard',
                        'description_2' => 'For most businesses that want to optimize web queries',
                        'monthly_price_2' => '29',
                        'yearly_price_2' => '348',
                        'features_2' => '+ All Basic Plan features \n + Access to premium vehicles \n + Flexible rental terms \n + GPS included \n + Free additional driver \n + Unlimited vehicle swaps',
                        'button_label_2' => 'Get Started Now',
                        'button_url_2' => '/',
                        'name_3' => 'Premium',
                        'description_3' => 'For most businesses that want to optimize web queries',
                        'monthly_price_3' => '49',
                        'yearly_price_3' => '585',
                        'features_3' => '+ All Standard Plan features \n + Luxury vehicle options \n + Complimentary upgrades \n + Enhanced insurance coverage \n + Free airport pickup, drop off \n + Exclusive deals and offers',
                        'button_label_3' => 'Get Started Now',
                        'button_url_3' => '/',
                        'name_4' => 'VIP',
                        'description_4' => 'For most businesses that want to optimize web queries',
                        'monthly_price_4' => '99',
                        'yearly_price_4' => '1185',
                        'features_4' => '+ All Premium Plan features \n + VIP transfer service \n + Personal concierge \n + Unlimited mileage \n + Luxury vehicle upgrades \n + 24/7 account manager',
                        'button_label_4' => 'Get Started Now',
                        'button_url_4' => '/',
                    ]) .
                    Shortcode::generateShortcode('faqs', [
                        'title' => 'Frequently Asked Questions',
                        'description' => 'Any questions? We would be happy to help you.',
                        'faq_category_ids' => '1,2,3,4,5',
                        'limit' => '10',
                        'button_secondary_label' => 'Contact Us',
                        'button_secondary_url' => '/contact',
                        'button_primary_label' => 'Submit A Ticket',
                        'button_primary_url' => '/',
                    ]),
                'template' => 'full-width',
                'metadata' => [
                    'breadcrumb_background_image' => $this->filePath('backgrounds/pricing-bg.jpg'),
                    'breadcrumb_text_color' => '#ffffff',
                ],
            ],
            [
                'name' => 'About Us',
                'content' => htmlentities(
                    Shortcode::generateShortcode('about-us-information', [
                        'title' => 'The Future of <br> Car Rental is Here',
                        'description' => 'Welcome to Carento, your trusted partner in car rentals. Since our founding, we have been committed to providing our customers with a seamless and reliable car rental experience. Whether you are planning a business trip, a family vacation, or just need a vehicle for everyday use, we offer a wide range of vehicles to meet your needs.',
                        'quantity' => '3',
                        'data_number_1' => '86',
                        'data_title_1' => 'Industry <br> Experts',
                        'image_1' => $this->filePath('general/about-us-1.jpg'),
                        'image_2' => $this->filePath('general/about-us-2.jpg'),
                        'data_number_3' => '25',
                        'data_title_3' => 'Years in Business',
                        'image_3' => $this->filePath('general/about-us-3.jpg'),
                    ]) .
                    Shortcode::generateShortcode('why-us', [
                        ...$this->genTabData(data: [
                            [
                                'card_image' => $this->filePath('icons/car-location.png'),
                                'card_title' => 'Choose a Location',
                                'card_content' => 'Select the ideal destination to begin your journey with ease',
                            ],
                            [
                                'card_image' => $this->filePath('icons/money.png'),
                                'card_title' => 'Transparent Pricing',
                                'card_content' => 'Enjoy clear and upfront pricing with no surprises, ensuring you know exactly what you are paying for.',
                            ],
                            [
                                'card_image' => $this->filePath('icons/convenient.png'),
                                'card_title' => 'Convenient Booking',
                                'card_content' => 'Benefit from a variety of rental options, including short-term, long-term, and weekend specials',
                            ],
                            [
                                'card_image' => $this->filePath('icons/supporter.png'),
                                'card_title' => '24/7 Customer Support',
                                'card_content' => 'Get assistance whenever you need it with our dedicated support team available around the clock.',
                            ],
                        ]),
                    ]) .
                    Shortcode::generateShortcode('team', [
                        'title' => 'Meet Our Agents',
                        'subtitle' => 'Awesome Teams',
                        'team_ids' => '1,2,3,5',
                    ]) .
                    Shortcode::generateShortcode('featured-block', [
                        'style' => 'style-2',
                        'title' => 'Sell your car at a fair price. Get started with us today.',
                        'subtitle' => 'Our Mission',
                        'description' => 'Our mission is to make car rental easy, accessible, and affordable for everyone. We believe that renting a car should be a hassle-free experience, and we are dedicated to ensuring that every customer finds the perfect vehicle for their journey.',
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
                        'background_color' => '#d8f4db',
                    ]) .
                    Shortcode::generateShortcode('intro-video', [
                        'style' => 'style-2',
                        'title' => 'Carento offers clear pricing and 24/7 great support.',
                        'subtitle' => 'Our Commitment',
                        'description' => 'We are committed to offering transparent pricing with no hidden fees, comprehensive insurance options for peace of mind, and 24/7 customer support to assist you whenever you need it. At Carento, your satisfaction is our top priority.',
                        'youtube_video_url' => 'https://www.youtube.com/watch?v=ldusxyoq0Y8',
                        'quantity' => 3,
                        'content_1' => 'Explore a wide range of flexible rental options to suit your needs',
                        'content_2' => 'Comprehensive insurance coverage for complete peace of mind',
                        'content_3' => '24/7 customer support for assistance anytime, anywhere',
                        'button_label' => 'Get Started Now',
                        'button_url' => '/contact',
                        'image' => $this->filePath('general/3.jpg'),
                        'image_1' => $this->filePath('general/2.jpg'),
                        'enable_lazy_loading' => false,
                    ]) .
                    Shortcode::generateShortcode('testimonials', [
                        'title' => 'What they say about us?',
                        'subtitle' => 'Testimonials',
                        'testimonial_ids' => '1,2,3,4',
                        'style' => 'style-2',
                        'enable_lazy_loading' => false,
                    ]) .
                    Shortcode::generateShortcode('blog-posts', [
                        'style' => 'style-1',
                        'title' => 'Upcoming Cars & Events',
                        'subtitle' => 'Stay ahead with the latest car releases and upcoming events',
                        'button_label' => 'Keep Reading',
                        'category_ids' => '1,2,3,4,5',
                        'limit' => 10,
                    ]),
                ),
                'template' => 'full-width',
                'metadata' => [
                    'breadcrumb_background_image' => $this->filePath('backgrounds/about-us-bg.jpg'),
                    'breadcrumb_text_color' => '#ffffff',
                ],
            ],
            [
                'name' => 'Car List 1',
                'content' => htmlentities(
                    Shortcode::generateShortcode('banner', [
                        'title' => 'Find Your Perfect Car',
                        'subtitle' => 'Search and find your best car rental with easy way',
                        'tag' => 'Find cars for sale and for rent near you',
                        'background_image' => $this->filePath('backgrounds/banner6.jpg'),
                    ]) .
                    Shortcode::generateShortcode('car-advance-search', [
                        'button_search_name' => 'Find a Vehicle',
                        'link_need_help' => '/faqs',
                        'top' => -124,
                        'bottom' => 0,
                        'left' => 0,
                        'right' => 0,
                        'tabs' => 'all,new_car,used_car',
                    ]) .
                    Shortcode::generateShortcode('car-list', [
                        'title' => 'Our Vehicle Fleet',
                        'subtitle' => 'Turning dreams into reality with versatile vehicles.',
                        'enable_filter' => 'yes',
                        'default_layout' => 'grid',
                    ]) .
                    Shortcode::generateShortcode('brands', [
                        'title' => '',
                        'subtitle' => '',
                        'brand_ids' => CarMake::query()->wherePublished()->pluck('id')->implode(','),
                        'button_label' => '',
                        'button_url' => '',
                    ])
                ),
                'template' => 'homepage',
            ],
            [
                'name' => 'Car List 2',
                'content' => htmlentities(
                    Shortcode::generateShortcode('banner', [
                        'title' => 'Find Your Perfect Car',
                        'subtitle' => 'Search and find your best car rental with easy way',
                        'tag' => 'Find cars for sale and for rent near you',
                        'background_image' => $this->filePath('backgrounds/banner6.jpg'),
                    ]) .
                    Shortcode::generateShortcode('car-advance-search', [
                        'button_search_name' => 'Find a Vehicle',
                        'link_need_help' => '/faqs',
                        'top' => -124,
                        'bottom' => 0,
                        'left' => 0,
                        'right' => 0,
                        'tabs' => 'all,new_car,used_car',
                    ]) .
                    Shortcode::generateShortcode('car-list', [
                        'title' => 'Our Vehicle Fleet',
                        'subtitle' => 'Turning dreams into reality with versatile vehicles.',
                        'enable_filter' => 'no',
                        'default_layout' => 'grid',
                        'layout_col' => 4,
                    ]) .
                    Shortcode::generateShortcode('brands', [
                        'title' => '',
                        'subtitle' => '',
                        'brand_ids' => CarMake::query()->wherePublished()->pluck('id')->implode(','),
                        'button_label' => '',
                        'button_url' => '',
                    ])
                ),
                'template' => 'homepage',
            ],
            [
                'name' => 'Car List 3',
                'content' => htmlentities(
                    Shortcode::generateShortcode('banner', [
                        'title' => 'Find Your Perfect Car',
                        'subtitle' => 'Search and find your best car rental with easy way',
                        'tag' => 'Find cars for sale and for rent near you',
                        'background_image' => $this->filePath('backgrounds/banner6.jpg'),
                    ]) .
                    Shortcode::generateShortcode('car-list', [
                        'title' => 'Our Vehicle Fleet',
                        'subtitle' => 'Turning dreams into reality with versatile vehicles.',
                        'enable_filter' => 'no',
                        'default_layout' => 'grid',
                        'layout_col' => 3,
                    ]) .
                    Shortcode::generateShortcode('brands', [
                        'title' => '',
                        'subtitle' => '',
                        'brand_ids' => CarMake::query()->wherePublished()->pluck('id')->implode(','),
                        'button_label' => '',
                        'button_url' => '',
                    ])
                ),
                'template' => 'homepage',
            ],
            [
                'name' => 'Car List 4',
                'content' => htmlentities(
                    Shortcode::generateShortcode('banner', [
                        'title' => 'Find Your Perfect Car',
                        'subtitle' => 'Search and find your best car rental with easy way',
                        'tag' => 'Find cars for sale and for rent near you',
                        'background_image' => $this->filePath('backgrounds/banner6.jpg'),
                    ]) .
                    Shortcode::generateShortcode('car-advance-search', [
                        'button_search_name' => 'Find a Vehicle',
                        'link_need_help' => '/faqs',
                        'top' => -124,
                        'bottom' => 0,
                        'left' => 0,
                        'right' => 0,
                        'tabs' => 'all,new_car,used_car',
                    ]) .
                    Shortcode::generateShortcode('car-list', [
                        'title' => 'Our Vehicle Fleet',
                        'subtitle' => 'Turning dreams into reality with versatile vehicles.',
                        'enable_filter' => 'yes',
                        'default_layout' => 'list',
                        'layout_col' => 4,
                    ]) .
                    Shortcode::generateShortcode('brands', [
                        'title' => '',
                        'subtitle' => '',
                        'brand_ids' => CarMake::query()->wherePublished()->pluck('id')->implode(','),
                        'button_label' => '',
                        'button_url' => '',
                    ])
                ),
                'template' => 'homepage',
            ],
            [
                'name' => 'Agencies',
                'content' =>
                    Shortcode::generateShortcode('team', [
                        'title' => 'Meet Our Agents',
                        'subtitle' => 'Awesome Teams',
                        'team_ids' => '1,2,3,4,5,6,7,8',
                    ]),
                'template' => 'full-width',
                'metadata' => [
                    'breadcrumb_background_image' => $this->filePath('backgrounds/pricing-bg.jpg'),
                    'breadcrumb_text_color' => '#ffffff',
                    'breadcrumb_display_last_update' => true,
                ],
            ],
            [
                'name' => 'Faqs',
                'content' => htmlentities(
                    Shortcode::generateShortcode('faq-categories', [
                        'title' => 'Frequently Asked Questions',
                        'description' => 'Any questions? We would be happy to help you.',
                    ]) .
                    Shortcode::generateShortcode('faqs', [
                        'title' => 'Frequently Asked Questions',
                        'description' => 'Any questions? We would be happy to help you.',
                        'faq_category_ids' => '1,2,3,4,5',
                        'limit' => '10',
                        'button_secondary_label' => 'Contact Us',
                        'button_secondary_url' => '/contact',
                        'button_primary_label' => 'Submit A Ticket',
                        'button_primary_url' => '/',
                    ]) .
                    Shortcode::generateShortcode('faqs', [
                        'title' => 'Frequently Asked Questions',
                        'description' => 'Any questions? We would be happy to help you.',
                        'faq_category_ids' => '1,2,3,4,5',
                        'limit' => '10',
                        'button_secondary_label' => 'Contact Us',
                        'button_secondary_url' => '/contact',
                        'button_primary_label' => 'Submit A Ticket',
                        'button_primary_url' => '/',
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
                    ]),
                ),
                'template' => 'full-width',
                'metadata' => [
                    'breadcrumb_simple' => true,
                ],
            ],
            [
                'name' => 'Brands',
                'content' => Shortcode::generateShortcode('brands', [
                    'title' => 'Brands',
                    'style' => 'style-3',
                    'brand_ids' => CarMake::query()->wherePublished()->pluck('id')->implode(','),
                ]) ,
                'metadata' => [
                    'breadcrumb_background_image' => $this->filePath('backgrounds/brand-bg.jpg'),
                    'breadcrumb_text_color' => '#ffffff',
                ],
            ],
        ];

        $this->createPages($pages);
        $this->createSimplePage();
    }

    protected function genTabData(array $data): array
    {
        $tabData = [];
        $quantity = 0;

        foreach ($data as $index => $feature) {
            $quantity++;
            foreach ($feature as $key => $value) {
                $tabData[$key . '_' . $index + 1] = $value;
            }
        }

        return [
            ...$tabData,
            'quantity' => $quantity,
        ];
    }

    protected function getSimpleContent(): string
    {
        return file_get_contents(database_path('seeders/contents/default-content.html'));
    }

    protected function createSimplePage(): void
    {
        $pageNames = [
            'Our Awards',
            'Copyright Notices',
            'Terms of Use',
            'Privacy Notice',
            'Lost & Found',
            'Car Rental Services',
            'Vehicle Leasing Options',
            'Long-Term Car Rentals',
            'Car Sales and Trade-Ins',
            'Luxury Car Rentals',
            'Rent-to-Own Programs',
            'Fleet Management Solutions',
            'Affiliates',
            'Travel Agents',
            'AARP Members',
            'Points Programs',
            'Military & Veterans',
            'Work with us',
            'Advertise with us',
            'Forum support',
            'Help Center',
            'Live chat',
            'How it works',
            'Security',
            'Refund Policy',
        ];

        $content = $this->getSimpleContent();

        $pages = array_map(function ($pageName) use ($content) {
            return [
                'name' => $pageName,
                'content' => $content,
                'template' => 'default',
                'metadata' => [
                    'breadcrumb_background_image' => $this->filePath('backgrounds/pricing-bg.jpg'),
                    'breadcrumb_text_color' => '#ffffff',
                ],
            ];
        }, $pageNames);

        $this->createPages($pages);
    }
}
