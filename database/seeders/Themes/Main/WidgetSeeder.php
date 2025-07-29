<?php

namespace Database\Seeders\Themes\Main;

use Botble\Base\Supports\BaseSeeder;
use Botble\CarRentals\Models\Service;
use Botble\Widget\Database\Traits\HasWidgetSeeder;
use Botble\Widget\Widgets\CoreSimpleMenu;
use Illuminate\Support\Str;

class WidgetSeeder extends BaseSeeder
{
    use HasWidgetSeeder;

    public function run(): void
    {
        $this->createWidgets($this->getWidgets());
    }

    protected function getWidgets(): array
    {
        return [
            ...$this->getDataPrimarySidebar(),
            ...$this->getDataFooterSidebar(),
            ...$this->getDataBottomFooterSidebar(),
            ...$this->getDataTopFooterSidebar(),
            ...$this->getDataHeaderTopSidebar(),
            ...$this->getDataBlogSidebar(),
            ...$this->getDataAboveBlogListSidebar(),
            ...$this->getDataOffCanvasSidebar(),
        ];
    }

    public function getDataPrimarySidebar(): array
    {
        return [];
    }

    public function getDataFooterSidebar(): array
    {
        $carServices = Service::query()
            ->wherePublished()
            ->orderBy('id', 'desc')
            ->limit(7)
            ->get();

        $serviceData = $carServices->map(function ($item) {
            return [
                [
                    'key' => 'label',
                    'value' => $item->name,
                ],
                [
                    'key' => 'url',
                    'value' => route('public.single', 'services/' . Str::slug($item->name)),
                ],
            ];
        });

        return [
            [
                'widget_id' => 'SiteInformationWidget',
                'sidebar_id' => 'footer_sidebar',
                'position' => 1,
                'data' => [
                    'id' => 'SiteInformationWidget',
                    'logo' => $this->filePath('general/logo-dark.png'),
                    'quantity' => 4,
                    'icon_1' => 'ti ti-map-pin',
                    'description_1' => '2356 Oakwood Drive, Suite 18, San Francisco, California 94111, US',
                    'icon_2' => 'ti ti-clock-hour-3',
                    'description_2' => 'Hours: 8:00 - 17:00, Mon - Sat',
                    'icon_3' => 'ti ti-mail',
                    'description_3' => 'support@carento.com',
                    'title_4' => 'Need help? Call us',
                    'icon_4' => 'ti ti-phone',
                    'description_4' => '<a href="tel:+1 222-555-33-99">+1 222-555-33-99</a>',
                ],
            ],
            [
                'widget_id' => CoreSimpleMenu::class,
                'sidebar_id' => 'footer_sidebar',
                'position' => 2,
                'data' => [
                    'id' => CoreSimpleMenu::class,
                    'name' => 'Company',
                    'items' => [
                        [
                            [
                                'key' => 'label',
                                'value' => 'About Us',
                            ],
                            [
                                'key' => 'url',
                                'value' => '/about-us',
                            ],
                        ],
                        [
                            [
                                'key' => 'label',
                                'value' => 'Our Awards',
                            ],
                            [
                                'key' => 'url',
                                'value' => '/our-awards',
                            ],
                        ],
                        [
                            [
                                'key' => 'label',
                                'value' => 'Agencies',
                            ],
                            [
                                'key' => 'url',
                                'value' => '/agencies',
                            ],
                        ],
                        [
                            [
                                'key' => 'label',
                                'value' => 'Copyright Notices',
                            ],
                            [
                                'key' => 'url',
                                'value' => '/copyright-notices',
                            ],
                        ],
                        [
                            [
                                'key' => 'label',
                                'value' => 'Terms of Use',
                            ],
                            [
                                'key' => 'url',
                                'value' => '/terms-of-use',
                            ],
                        ],
                        [
                            [
                                'key' => 'label',
                                'value' => 'Privacy Notice',
                            ],
                            [
                                'key' => 'url',
                                'value' => '/privacy-notice',
                            ],
                        ],
                        [
                            [
                                'key' => 'label',
                                'value' => 'Lost & Found',
                            ],
                            [
                                'key' => 'url',
                                'value' => '/lost-found',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'widget_id' => CoreSimpleMenu::class,
                'sidebar_id' => 'footer_sidebar',
                'position' => 3,
                'data' => [
                    'id' => CoreSimpleMenu::class,
                    'name' => 'Our Services',
                    'items' => $serviceData,
                ],
            ],
            [
                'widget_id' => CoreSimpleMenu::class,
                'sidebar_id' => 'footer_sidebar',
                'position' => 4,
                'data' => [
                    'id' => CoreSimpleMenu::class,
                    'name' => 'Our Partners',
                    'items' => [
                        [
                            [
                                'key' => 'label',
                                'value' => 'Affiliates',
                            ],
                            [
                                'key' => 'url',
                                'value' => '/affiliates',
                            ],
                        ],
                        [
                            [
                                'key' => 'label',
                                'value' => 'Travel Agents',
                            ],
                            [
                                'key' => 'url',
                                'value' => '/travel-agents',
                            ],
                        ],
                        [
                            [
                                'key' => 'label',
                                'value' => 'AARP Members',
                            ],
                            [
                                'key' => 'url',
                                'value' => '/aarp-members',
                            ],
                        ],
                        [
                            [
                                'key' => 'label',
                                'value' => 'Points Programs',
                            ],
                            [
                                'key' => 'url',
                                'value' => '/points-programs',
                            ],
                        ],
                        [
                            [
                                'key' => 'label',
                                'value' => 'Military & Veterans',
                            ],
                            [
                                'key' => 'url',
                                'value' => '/military-veterans',
                            ],
                        ],
                        [
                            [
                                'key' => 'label',
                                'value' => 'Work with us',
                            ],
                            [
                                'key' => 'url',
                                'value' => '/work-with-us',
                            ],
                        ],
                        [
                            [
                                'key' => 'label',
                                'value' => 'Advertise with us',
                            ],
                            [
                                'key' => 'url',
                                'value' => '/advertise-with-us',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'widget_id' => CoreSimpleMenu::class,
                'sidebar_id' => 'footer_sidebar',
                'position' => 5,
                'data' => [
                    'id' => CoreSimpleMenu::class,
                    'name' => 'Support',
                    'items' => [
                        [
                            [
                                'key' => 'label',
                                'value' => 'Forum support',
                            ],
                            [
                                'key' => 'url',
                                'value' => '/forum-support',
                            ],
                        ],
                        [
                            [
                                'key' => 'label',
                                'value' => 'Help Center',
                            ],
                            [
                                'key' => 'url',
                                'value' => '/help-center',
                            ],
                        ],
                        [
                            [
                                'key' => 'label',
                                'value' => 'Live chat',
                            ],
                            [
                                'key' => 'url',
                                'value' => '/live-chat',
                            ],
                        ],
                        [
                            [
                                'key' => 'label',
                                'value' => 'How it works',
                            ],
                            [
                                'key' => 'url',
                                'value' => '/how-it-works',
                            ],
                        ],
                        [
                            [
                                'key' => 'label',
                                'value' => 'Security',
                            ],
                            [
                                'key' => 'url',
                                'value' => '/security',
                            ],
                        ],
                        [
                            [
                                'key' => 'label',
                                'value' => 'Refund Policy',
                            ],
                            [
                                'key' => 'url',
                                'value' => '/refund-policy',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    public function getDataTopFooterSidebar(): array
    {
        return [
            [
                'widget_id' => 'NewsletterWidget',
                'sidebar_id' => 'top_footer_sidebar',
                'position' => 1,
                'data' => [
                    'id' => 'NewsletterWidget',
                    'title' => 'Subscribe to see secret deals prices drop the moment you sign up!',
                    'button_label' => 'Subscribe',
                ],
            ],
        ];
    }

    public function getDataBottomFooterSidebar(): array
    {
        return [
            [
                'widget_id' => 'SiteCopyrightWidget',
                'sidebar_id' => 'bottom_footer_sidebar',
                'position' => 1,
                'data' => [],
            ],
            [
                'widget_id' => 'SocialLinksWidget',
                'sidebar_id' => 'bottom_footer_sidebar',
                'position' => 2,
                'data' => [
                    'id' => 'SocialLinksWidget',
                    'title' => '',
                ],
            ],
        ];
    }

    public function getDataHeaderTopSidebar(): array
    {
        return [
            [
                'widget_id' => 'ContactInformationWidget',
                'sidebar_id' => 'header_top_sidebar',
                'position' => 1,
                'data' => [
                    'id' => 'ContactInformationWidget',
                    'quantity' => 2,
                    'title_1' => '+123 9898 500',
                    'icon_1' => 'ti ti-phone-call',
                    'url_1' => 'tel:123 9898 500',
                    'title_2' => 'sale@carento.com',
                    'icon_2' => 'ti ti-mail',
                    'url_2' => 'mailto:sale@carento.com',
                ],
            ],
        ];
    }

    public function getDataBlogSidebar(): array
    {
        return [
            [
                'widget_id' => 'BlogSearchWidget',
                'sidebar_id' => 'blog_sidebar',
                'position' => 1,
                'data' => [
                    'id' => 'BlogSearchWidget',
                ],
            ],
            [
                'widget_id' => 'BlogPostsWidget',
                'sidebar_id' => 'blog_sidebar',
                'position' => 2,
                'data' => [
                    'id' => 'BlogPostsWidget',
                    'title' => 'Latest Posts',
                    'category_ids' => [1, 2, 3, 4, 5],
                    'limit' => 5,
                ],
            ],
            [
                'widget_id' => 'GalleriesWidget',
                'sidebar_id' => 'blog_sidebar',
                'position' => 3,
                'data' => [
                    'id' => 'GalleriesWidget',
                    'title' => 'Instagram Posts',
                    'limit' => 9,
                ],
            ],
        ];
    }

    public function getDataAboveBlogListSidebar(): array
    {
        return [
            [
                'widget_id' => 'FeaturedPostsWidget',
                'sidebar_id' => 'above_blog_list_sidebar',
                'position' => 1,
                'data' => [
                    'id' => 'FeaturedPostsWidget',
                    'title' => 'Inside & Trending',
                    'category_ids' => [1, 2, 3, 4, 5, 6],
                    'limit' => 4,
                ],
            ],
        ];
    }

    public function getDataOffCanvasSidebar(): array
    {
        return [
            [
                'widget_id' => 'BlogPostsWidget',
                'sidebar_id' => 'off_canvas_sidebar',
                'position' => 1,
                'data' => [
                    'id' => 'BlogPostsWidget',
                    'title' => 'Latest Posts',
                    'category_ids' => [1, 2, 3, 4, 5],
                    'limit' => 5,
                ],
            ],
            [
                'widget_id' => 'SiteInformationWidget',
                'sidebar_id' => 'off_canvas_sidebar',
                'position' => 2,
                'data' => [
                    'id' => 'SiteInformationWidget',
                    'logo' => $this->filePath('icons/contact.png'),
                    'quantity' => 4,
                    'icon_1' => 'ti ti-map-pin',
                    'description_1' => '2356 Oakwood Drive, Suite 18, San Francisco, California 94111, US',
                    'icon_2' => 'ti ti-clock-hour-3',
                    'description_2' => 'Hours: 8:00 - 17:00, Mon - Sat',
                    'icon_3' => 'ti ti-mail',
                    'description_3' => 'support@carento.com',
                ],
            ],
        ];
    }
}
