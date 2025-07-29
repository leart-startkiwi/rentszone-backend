<?php

namespace Database\Seeders\Themes\Main;

use Botble\Base\Facades\MetaBox;
use Botble\Base\Supports\BaseSeeder;
use Botble\Language\Models\LanguageMeta;
use Botble\Setting\Facades\Setting;
use Botble\Shortcode\Facades\Shortcode;
use Botble\SimpleSlider\Models\SimpleSlider;
use Botble\SimpleSlider\Models\SimpleSliderItem;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class SimpleSliderSeeder extends BaseSeeder
{
    /**
     * @throws FileNotFoundException
     */
    public function run(): void
    {
        SimpleSlider::query()->truncate();
        SimpleSliderItem::query()->truncate();

        $this->uploadFiles('sliders');

        foreach ($this->getSliders() as $parent) {
            /**
             * @var SimpleSlider $slider
             */
            $slider = SimpleSlider::query()->create(array_merge(Arr::except($parent, ['children', 'metadata']), [
                'key' => Str::slug($parent['name']),
            ]));

            foreach ($parent['metadata'] as $parentMetaKey => $parentMetaValue) {
                MetaBox::saveMetaBoxData($slider, $parentMetaKey, $parentMetaValue);
            }
            LanguageMeta::saveMetaData($slider);

            foreach ($parent['children'] as $key => $item) {
                $sliderItem = $slider->sliderItems()->create([
                    ...Arr::except($item, ['metadata']),
                    'order' => $key,
                ]);

                foreach ($item['metadata'] as $metaKey => $metaValue) {
                    MetaBox::saveMetaBoxData($sliderItem, $metaKey, $metaValue);
                }
            }
        }

        Setting::set('simple_slider_using_assets', false)->save();
    }

    protected function getSliders(): array
    {
        return [
            [
                'name' => 'Home slider',
                'description' => 'The main slider on homepage',
                'metadata' => [
                    'content_on_top' => Shortcode::generateShortcode('car-advance-search', [
                        'button_search_name' => 'Find a Vehicle',
                        'link_need_help' => '/faqs',
                        'top' => 0,
                        'bottom' => 0,
                        'left' => 0,
                        'right' => 0,
                        'url' => '/cars',
                        'tabs' => 'all,new_car,used_car',
                    ]),
                    'footer_on_top' => '<p><span class="text-lg-medium color-white">Get 5% discount when </span><a class="text-primary" href="#install-app"><span class="text-lg-medium">Ordering via APP</span></a></p>',
                ],
                'children' => [
                    [
                        'title' => 'Find your next vehicle today',
                        'description' => 'Browse our diverse inventory and enjoy a seamless buying experience <br> with expert support every step of the way',
                        'link' => '/',
                        'image' => $this->filePath('sliders/banner-1.jpg'),
                        'metadata' => [
                            'label_top' => '+3600 cars for you',
                            'keywords' => [
                                [
                                    'name' => 'Economy',
                                    'link' => '/',
                                ],
                                [
                                    'name' => 'Standard',
                                    'link' => '/',
                                ],
                                [
                                    'name' => 'Luxury',
                                    'link' => '/',
                                ],
                                [
                                    'name' => 'SUV',
                                    'link' => '/',
                                ],
                                [
                                    'name' => 'Convertible',
                                    'link' => '/',
                                ],
                            ],
                        ],
                    ],
                    [
                        'title' => 'Discover your next ride today',
                        'description' => 'Explore our wide selection and enjoy a smooth purchasing journey, <br> with expert assistance at every turn',
                        'link' => '/',
                        'image' => $this->filePath('sliders/banner-2.jpg'),
                        'metadata' => [
                            'label_top' => 'Best car rental system',
                            'keywords' => [
                                [
                                    'name' => 'Economy',
                                    'link' => '/',
                                ],
                                [
                                    'name' => 'Standard',
                                    'link' => '/',
                                ],
                                [
                                    'name' => 'Luxury',
                                    'link' => '/',
                                ],
                                [
                                    'name' => 'SUV',
                                    'link' => '/',
                                ],
                                [
                                    'name' => 'Convertible',
                                    'link' => '/',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Home slider 02',
                'description' => 'The slider in homepage page 2',
                'metadata' => [
                    'appearance' => 'style-2',
                ],
                'children' => [
                    [
                        'title' => 'CAR REVIEW',
                        'description' => 'The Tucson Plug-in Hybrid is easy to drive and provides a sufficient all-electric range.',
                        'link' => '/',
                        'image' => $this->filePath('sliders/img-1-1.jpg'),
                        'metadata' => [
                            'subtitle' => '2025 Mazda CX-50 <br class="d-none d-md-block"> Review and news',
                            'link_label' => 'View Details',
                        ],
                    ],
                    [
                        'title' => 'CAR REVIEW',
                        'description' => 'The Tucson Plug-in Hybrid is easy to drive and provides a sufficient all-electric range.',
                        'link' => '/',
                        'image' => $this->filePath('sliders/img-1.jpg'),
                        'metadata' => [
                            'subtitle' => 'Hyundai Tucson Plug-In <br class="d-none d-md-block"> Hybrid 2025 review',
                            'link_label' => 'View Details',
                        ],
                    ],
                ],
            ],
        ];
    }
}
