<?php

namespace Database\Seeders\Themes\Main;

use Botble\ACL\Models\User;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Facades\Html;
use Botble\Base\Supports\BaseSeeder;
use Botble\Slug\Facades\SlugHelper;
use Botble\Team\Models\Team;
use Illuminate\Support\Arr;

class TeamSeeder extends BaseSeeder
{
    public function run(): void
    {
        $files = $this->uploadFiles('teams');

        $content =
            Html::tag('p', 'Our diverse team of experts brings a wealth of knowledge and experience across various industries. We are united by a shared passion for excellence and a commitment to providing innovative solutions for your business needs. Get to know the faces driving our success and learn how their expertise can contribute to yours.');

        $teams = [
            [
                'name' => 'Devon Lane',
                'title' => 'Chief Executive Officer (CEO)',
                'location' => 'India',
                'phone' => '01123259241',
                'email' => 'devonsoland111@gmail.com',
                'address' => '4855, 24, Ansari Road, Darya Ganj',
            ],
            [
                'name' => 'Marvin McKinney',
                'title' => 'Fleet Manager',
                'location' => 'Thailand',
                'phone' => '6623742088',
                'email' => 'marvinkensy@gmail.com',
                'address' => '849 Sukhapibal 1 Klong Chan Bang Kapi',
            ],
            [
                'name' => 'Ronald Richards',
                'title' => 'Customer Service Representative',
                'location' => 'Canada',
                'phone' => '4165550123',
                'email' => 'ronrichards@marketing.com',
                'address' => '123 Maple Street, Toronto, ON',
            ],
            [
                'name' => 'Cameron Williamson',
                'title' => 'Finance & Accounting Officer',
                'location' => 'Germany',
                'phone' => '49221567890',
                'email' => 'cameronwill@tech.com',
                'address' => '45 Hauptstraße, 50667 Köln',
            ],
            [
                'name' => 'Alicia Sanders',
                'title' => 'Marketing Executive',
                'location' => 'Australia',
                'phone' => '0298765432',
                'email' => 'alicia.hr@company.com',
                'address' => '15 George St, Sydney, NSW',
            ],
            [
                'name' => 'Ethan Wright',
                'title' => 'Automotive Technician/Mechanic',
                'location' => 'South Africa',
                'phone' => '0217896543',
                'email' => 'ethanwright@operations.com',
                'address' => '789 Beach Road, Cape Town',
            ],
            [
                'name' => 'Isabella Johnson',
                'title' => 'Operations Coordinator',
                'location' => 'New Zealand',
                'phone' => '0498761234',
                'email' => 'isabella.prod@company.com',
                'address' => '32 Victoria St, Wellington',
            ],
            [
                'name' => 'Liam Brown',
                'title' => 'HR & Administration Officer',
                'location' => 'United Kingdom',
                'phone' => '02079461234',
                'email' => 'liam.legal@company.co.uk',
                'address' => '10 Downing Street, London',
            ],
        ];

        Team::query()->truncate();

        $description = 'Sharing content online allows you to craft an online persona that reflects your personal values and professional skills. Even if you only use social media occasionally';

        foreach ($teams as $index => $item) {
            $item['content'] = $content;
            $item['socials'] = [
                'facebook' => 'https://www.facebook.com/',
                'twitter' => 'https://twitter.com/',
                'instagram' => 'https://www.instagram.com/',
            ];

            $item['status'] = BaseStatusEnum::PUBLISHED;
            $item['description'] = $description;
            $item['photo'] = $this->getFilePath($index);

            /**
             * @var Team $team
             */
            $team = Team::query()->create($item);

            SlugHelper::createSlug($team);
        }

        foreach (User::query()->get() as $user) {
            $user->avatar_id = Arr::random($files)['data']->id;
            $user->save();
        }
    }

    public function getFilePath(int $index): string
    {
        return $this->filePath('teams/' . $index + 1 . '.jpg');
    }
}
