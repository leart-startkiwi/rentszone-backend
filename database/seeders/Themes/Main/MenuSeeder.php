<?php

namespace Database\Seeders\Themes\Main;

use Botble\Base\Supports\BaseSeeder;
use Botble\Menu\Database\Traits\HasMenuSeeder;
use Botble\Page\Database\Traits\HasPageSeeder;
use Botble\Page\Models\Page;

class MenuSeeder extends BaseSeeder
{
    use HasMenuSeeder;
    use HasPageSeeder;

    public function run(): void
    {
        $data = [
            [
                'name' => 'Main menu',
                'slug' => 'main-menu',
                'location' => 'main-menu',
                'items' => [
                    [
                        'title' => 'Home',
                        'url' => '/',
                        'children' => [
                            [
                                'title' => 'Home Page v1',
                                'url' => 'https://carento.botble.com',
                            ],
                            [
                                'title' => 'Home Page v2',
                                'url' => 'https://carento-home-2.botble.com',
                            ],
                            [
                                'title' => 'Home Page v3',
                                'url' => 'https://carento-home-3.botble.com',
                            ],
                        ],
                    ],
                    [
                        'title' => 'Vehicles',
                        'url' => '/',
                        'children' => [
                            [
                                'title' => 'Cars List v1',
                                'reference_type' => Page::class,
                                'reference_id' => $this->getPageId('Car List 1'),
                            ],
                            [
                                'title' => 'Car Detail v1',
                                'url' => '/cars/honda-accord-sport-20t-2024',
                            ],
                            [
                                'title' => 'Car List v2',
                                'reference_type' => Page::class,
                                'reference_id' => $this->getPageId('Car List 2'),
                            ],
                            [
                                'title' => 'Car Detail v2',
                                'url' => '/cars/honda-accord-sport-20t-2024?style=style-2',
                            ],
                            [
                                'title' => 'Car List v3',
                                'reference_type' => Page::class,
                                'reference_id' => $this->getPageId('Car List 3'),
                            ],
                            [
                                'title' => 'Car Detail v3',
                                'url' => '/cars/honda-accord-sport-20t-2024?style=style-3',
                            ],
                            [
                                'title' => 'Car List v4',
                                'reference_type' => Page::class,
                                'reference_id' => $this->getPageId('Car List 4'),
                            ],
                            [
                                'title' => 'Car Detail v4',
                                'url' => '/cars/honda-accord-sport-20t-2024?style=style-4',
                            ],
                        ],
                    ],
                    [
                        'title' => 'Pages',
                        'url' => '/',
                        'children' => [
                            [
                                'title' => 'About Us',
                                'reference_type' => Page::class,
                                'reference_id' => $this->getPageId('About Us'),
                            ],
                            [
                                'title' => 'Our Services',
                                'reference_type' => Page::class,
                                'reference_id' => $this->getPageId('Services'),
                            ],
                            [
                                'title' => 'Pricing',
                                'reference_type' => Page::class,
                                'reference_id' => $this->getPageId('Pricing'),
                            ],
                            [
                                'title' => 'FAQs',
                                'reference_type' => Page::class,
                                'reference_id' => $this->getPageId('Faqs'),
                            ],
                            [
                                'title' => 'Term',
                                'reference_type' => Page::class,
                                'reference_id' => $this->getPageId('Terms of Use'),
                            ],
                            [
                                'title' => 'Login',
                                'url' => '/login',
                            ],
                            [
                                'title' => 'Register',
                                'url' => '/register',
                            ],
                        ],
                    ],
                    [
                        'title' => 'News',
                        'url' => '/',
                        'children' => [
                            [
                                'title' => 'News Grid',
                                'reference_type' => Page::class,
                                'reference_id' => $this->getPageId('Blog'),
                            ],
                            [
                                'title' => 'News List',
                                'url' => '/blog?style=list',
                            ],
                            [
                                'title' => 'New Detail',
                                'url' => '/news/top-5-new-cars-to-look-out-for-in-2024',
                            ],
                        ],
                    ],
                    [
                        'title' => 'Contact',
                        'reference_type' => Page::class,
                        'reference_id' => $this->getPageId('Contact'),
                    ],
                ],
            ],
        ];

        $this->createMenus($data);
    }
}
