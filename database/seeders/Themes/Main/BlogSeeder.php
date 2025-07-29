<?php

namespace Database\Seeders\Themes\Main;

use Botble\Base\Supports\BaseSeeder;
use Botble\Blog\Database\Traits\HasBlogSeeder;
use Botble\Shortcode\Facades\Shortcode;
use Illuminate\Support\Facades\File;

class BlogSeeder extends BaseSeeder
{
    use HasBlogSeeder;

    public function run(): void
    {
        $this->uploadFiles('news');

        $categories = [
            ['name' => 'New Cars'],
            ['name' => 'Car Rentals'],
            ['name' => 'Electric Vehicles'],
            ['name' => 'Car Maintenance'],
            ['name' => 'Road Trips'],
            ['name' => 'Luxury Cars'],
            ['name' => 'Family Cars'],
            ['name' => 'Off-Road Vehicles'],
            ['name' => 'Hybrid Cars'],
            ['name' => 'Car Innovations'],
        ];

        $this->createBlogCategories($categories);

        $tags = [
            ['name' => '2024 Cars'],
            ['name' => 'Car Leasing'],
            ['name' => 'Self-Driving Cars'],
            ['name' => 'Eco-Friendly'],
            ['name' => 'Car Subscription'],
            ['name' => 'Car Insurance'],
            ['name' => 'Used Cars'],
            ['name' => 'Car Detailing'],
            ['name' => 'Holiday Rentals'],
            ['name' => 'Cross-Country Trips'],
        ];

        $this->createBlogTags($tags);

        $posts = [
            [
                'name' => 'Top 5 New Cars to Look Out for in 2024',
                'description' => 'Discover the most anticipated car models coming in 2024, featuring cutting-edge technology and stunning designs.',
            ],
            [
                'name' => 'How to Choose the Best Car Rental Service for Your Trip',
                'description' => 'A comprehensive guide on selecting the right car rental service based on your travel needs and budget.',
            ],
            [
                'name' => 'The Evolution of Electric Vehicles: A New Era',
                'description' => 'Explore how electric cars are transforming the auto industry and why they are the future of transportation.',
            ],
            [
                'name' => 'Leasing vs. Buying a Car: Which Is Right for You?',
                'description' => 'An in-depth comparison of leasing and buying a car, helping you decide which option suits your lifestyle.',
            ],
            [
                'name' => 'The Ultimate Road Trip Checklist',
                'description' => 'Everything you need to pack and check before embarking on an unforgettable road trip adventure.',
            ],
            [
                'name' => 'The Benefits of Renting a Luxury Car for Special Occasions',
                'description' => 'Find out why renting a luxury vehicle can make your events even more memorable and stylish.',
            ],
            [
                'name' => 'Tips for Maintaining Your Car to Extend Its Lifespan',
                'description' => 'Essential tips on how to keep your car in top condition, ensuring it lasts longer and performs better.',
            ],
            [
                'name' => 'Top Safety Features to Look for in a Family Car',
                'description' => 'A guide to the latest safety innovations in family vehicles and how they protect your loved ones on the road.',
            ],
            [
                'name' => 'How Self-Driving Cars Are Changing the Future of Transportation',
                'description' => 'An overview of autonomous vehicles and the potential they have to reshape the way we travel.',
            ],
            [
                'name' => 'The Best Cars for Off-Road Adventures',
                'description' => 'Discover the top vehicles that offer exceptional performance on rough terrains for your next outdoor adventure.',
            ],
            [
                'name' => 'The Rise of Car Subscription Services',
                'description' => 'Learn about the growing trend of car subscription services and why more drivers are opting for this flexible alternative to car ownership.',
            ],
            [
                'name' => 'Eco-Friendly Driving Tips to Reduce Your Carbon Footprint',
                'description' => 'Simple strategies for eco-conscious drivers to minimize their environmental impact on the road.',
            ],
            [
                'name' => 'The Future of Car Sharing: Convenience at Your Fingertips',
                'description' => 'Explore how car sharing platforms are making it easier for people to access vehicles without the commitment of ownership.',
            ],
            [
                'name' => 'How to Get the Best Deals on Car Rentals During Holidays',
                'description' => 'Insider tips on how to save money and secure great deals when renting cars for your holiday travels.',
            ],
            [
                'name' => 'The Pros and Cons of Hybrid Vehicles',
                'description' => 'An analysis of the advantages and disadvantages of hybrid cars, helping you decide if they are the right choice for you.',
            ],
            [
                'name' => 'How to Properly Clean and Detail Your Car',
                'description' => 'Step-by-step instructions on cleaning your car inside and out to keep it looking brand new.',
            ],
            [
                'name' => 'Car Innovations: What to Expect in the Next 5 Years',
                'description' => 'A look at the technological advancements expected to revolutionize cars in the near future.',
            ],
            [
                'name' => 'The Ultimate Guide to Buying a Used Car',
                'description' => 'Everything you need to know about purchasing a used vehicle, from inspecting it to negotiating the best price.',
            ],
            [
                'name' => 'How to Plan a Cross-Country Trip with a Rented Car',
                'description' => 'Tips on organizing a successful cross-country road trip, including how to choose the right rental car.',
            ],
            [
                'name' => 'What You Need to Know About Car Insurance Before Renting',
                'description' => 'An essential guide to understanding car rental insurance and how to choose the right coverage for your trip.',
            ],
        ];

        foreach ($posts as $index => &$item) {
            $item['is_featured'] = true;
            $item['image'] = $this->filePath('news/' . ($index + 1) . '.jpg');
            $item['content'] = str_replace([
                '[content-images]',
                '[content-columns]',
            ], [
                Shortcode::generateShortcode('content-images', [
                    'quantity' => 2,
                    'image_1' => $this->filePath('news/' . (rand(1, 10)) . '.jpg'),
                    'image_2' => $this->filePath('news/' . (rand(1, 10)) . '.jpg'),
                ]),
                Shortcode::generateShortcode('content-columns', [
                    'quantity' => 2,
                    'content_1' => $this->fake()->text(400),
                    'content_2' => $this->fake()->text(400),
                ]),
            ], File::get(database_path('seeders/contents/post.html')));
        }

        $this->createBlogPosts($posts);
    }
}
