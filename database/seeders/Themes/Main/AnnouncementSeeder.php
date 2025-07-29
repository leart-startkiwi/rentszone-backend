<?php

namespace Database\Seeders\Themes\Main;

use ArchiElite\Announcement\Models\Announcement;
use Botble\Base\Supports\BaseSeeder;
use Botble\Setting\Facades\Setting;
use Carbon\Carbon;

class AnnouncementSeeder extends BaseSeeder
{
    public function run(): void
    {
        Announcement::query()->truncate();

        $announcements = [
            'Drive Your Dream Car Today – Affordable Rentals at Your Fingertips!',
            'Hit the Road in Style – Premium Car Rentals for Every Journey!',
            'Explore the Open Road – Hassle-Free Car Rentals Just a Click Away!',
        ];

        $now = Carbon::now();

        foreach ($announcements as $key => $value) {
            Announcement::query()->create([
                'name' => sprintf('Announcement %s', $key + 1),
                'content' => $value,
                'start_date' => $now,
                'dismissible' => true,
                'has_action' => true,
                'action_label' => 'Book Now',
                'action_url' => '/',
            ]);
        }

        $settings = [
            'announcement_max_width' => '1210',
            'announcement_text_color' => '#FFFFFF',
            'announcement_background_color' => 'transparent',
            'announcement_text_alignment' => 'start',
            'announcement_dismissible' => '1',
            'announcement_font_size' => 14,
            'announcement_font_size_unit' => 'px',
            'announcement_placement' => 'theme',
        ];

        Setting::delete(array_keys($settings));

        Setting::set($settings)->save();
    }
}
