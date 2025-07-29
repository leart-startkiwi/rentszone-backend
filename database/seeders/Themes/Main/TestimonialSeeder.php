<?php

namespace Database\Seeders\Themes\Main;

use Botble\Base\Facades\MetaBox;
use Botble\Base\Supports\BaseSeeder;
use Botble\Testimonial\Models\Testimonial;

class TestimonialSeeder extends BaseSeeder
{
    public function run(): void
    {
        $this->uploadFiles('testimonials');

        $testimonials = [
            [
                'name' => 'Guy Hawkins',
                'company' => 'CEO, JAKS Shans',
                'content' => 'Exceptional service! Gerow’s attention to detail and reliability have been instrumental in our supply chain success.',
            ],
            [
                'name' => 'Eleanor Pena',
                'company' => 'SEO, ChenTech Solutions',
                'content' => 'Gerow has consistently met and exceeded our logistics needs. Their dedication to excellence is truly commendable.',
            ],
            [
                'name' => 'Cody Fisher',
                'company' => 'Developer, Moie Agency',
                'content' => 'Their team is a valuable asset to our business operations. Gerow’s efficient service has saved us time and money.',
            ],
            [
                'name' => 'Albert Flores',
                'company' => 'CEO, Bank of America',
                'content' => 'Gerow’s attention to detail and professionalism have made them our preferred logistics partner. Highly recommended!',
            ],
        ];

        Testimonial::query()->truncate();

        foreach ($testimonials as $index => $item) {
            $item['image'] = $this->filePath(sprintf('testimonials/%d.png', $index + 1));

            $testimonial = Testimonial::query()->create($item);

            MetaBox::saveMetaBoxData($testimonial, 'rating_star', 5);
        }
    }
}
