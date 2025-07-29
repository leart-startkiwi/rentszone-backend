<?php

namespace Database\Seeders\Themes\Home2;

class ThemeOptionSeeder extends \Database\Seeders\Themes\Main\ThemeOptionSeeder
{
    public function getThemeOptions(): array
    {
        return [
            ...parent::getThemeOptions(),
            'is_header_transparent' => false,
            'logo' => $this->filePath('general/logo.png'),
            'header_top_background_color' => '#000000',
        ];
    }
}
