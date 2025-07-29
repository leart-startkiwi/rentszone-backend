<?php

namespace Database\Seeders\Themes\Home3;

class ThemeOptionSeeder extends \Database\Seeders\Themes\Main\ThemeOptionSeeder
{
    public function getThemeOptions(): array
    {
        return [
            ...parent::getThemeOptions(),
            'is_header_transparent' => false,
            'logo' => $this->filePath('general/logo.png'),
            'display_header_top' => true,
        ];
    }
}
