<?php

namespace Database\Seeders\Themes\Main;

use Botble\Base\Supports\BaseSeeder;
use Botble\Page\Database\Traits\HasPageSeeder;
use Botble\Theme\Database\Traits\HasThemeOptionSeeder;
use Botble\Theme\Supports\ThemeSupport;

class ThemeOptionSeeder extends BaseSeeder
{
    use HasPageSeeder;
    use HasThemeOptionSeeder;

    public function run(): void
    {
        $this->createThemeOptions($this->getThemeOptions());
    }

    protected function getThemeOptions(): array
    {
        return [
            'site_title' => 'Carento - Car Rentals Laravel Script',
            'seo_description' => 'Carento is a robust Laravel script for managing car rental businesses, featuring advanced booking, real-time vehicle availability, and customizable options for efficient operations.',
            'copyright' => '©%Y Botble Team. All Rights Reserved.',
            'favicon' => $this->filePath('general/favicon.png'),
            'logo' => $this->filePath('general/logo.png'),
            'logo_dark' => $this->filePath('general/logo-dark.png'),
            'logo_height' => 76,
            'primary_font' => 'Urbanist',
            'secondary_font' => 'Urbanist',
            'primary_color' => '#82b440',
            'primary_color_hover' => '#7aa93c',
            'secondary_color' => 'rgba(45, 74, 44, 0.6)',
            'heading_color' => '#000000',
            'text_color' => '#454545',
            'header_top_background_color' => '#000000',
            'header_top_text_color' => '#ffffff',
            'is_header_transparent' => true,
            'preloader_enabled' => true,
            'preloader_version' => 'v2',
            'breadcrumb_background_image' => $this->filePath('backgrounds/service-bg.jpg'),
            'homepage_id' => $this->getPageId('Homepage'),
            'blog_page_id' => $this->getPageId('Blog'),
            'blog_post_list_page_title' => 'Recent Posts',
            'blog_post_list_page_description' => 'Favorite vehicles based on customer reviews',
            'blog_post_gird_items_per_row' => 2,
            'blog_post_style' => 'grid',
            'social_links' => ThemeSupport::getDefaultSocialLinksData(),
            'footer_text_color' => '#8e8e8e',
            'footer_heading_color' => '#ffffff',
            'footer_border_color' => '#5756567d',
            'footer_background_color' => '#000000',
            'newsletter_popup_enable' => true,
            'newsletter_popup_image' => $this->filePath('backgrounds/newsletter-bg.jpg'),
            'newsletter_popup_title' => 'Special Offers',
            'newsletter_popup_subtitle' => 'Newsletter',
            'newsletter_popup_description' => 'Special Offer: Rent Your Car Today!',
        ];
    }
}
