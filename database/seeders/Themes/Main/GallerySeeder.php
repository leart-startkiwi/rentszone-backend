<?php

namespace Database\Seeders\Themes\Main;

use Botble\Base\Supports\BaseSeeder;
use Botble\Gallery\Database\Traits\HasGallerySeeder;

class GallerySeeder extends BaseSeeder
{
    use HasGallerySeeder;

    public function run(): void
    {
        $this->uploadFiles('galleries');

        $galleries = [
            'Stunning Electric Cars of 2024',
            'Top Luxury Cars for Special Occasions',
            'Family Cars with Advanced Safety Features',
            'Off-Road Vehicles in Action',
            'The Evolution of Car Design: A Visual Journey',
            'Best Road Trip Cars of the Year',
            'Exclusive New Car Models Unveiled',
            'Iconic Cars from Around the World',
            'The Future of Electric and Hybrid Cars',
            'Luxury Car Interiors: A Closer Look',
        ];

        $this->createGalleries(
            collect($galleries)->map(function (string $item, int $index) {
                return ['name' => $item, 'image' => $this->filePath('galleries/' . ($index + 1) . '.jpg')];
            })->all(),
            array_map(function ($index) {
                return ['img' => $this->filePath('galleries/' . $index . '.jpg'), 'description' => ''];
            }, range(1, 8))
        );
    }
}
