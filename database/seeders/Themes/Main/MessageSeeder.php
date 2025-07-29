<?php

namespace Database\Seeders\Themes\Main;

use Botble\Base\Supports\BaseSeeder;
use Botble\CarRentals\Enums\MessageStatusEnum;
use Botble\CarRentals\Models\Customer;
use Botble\CarRentals\Models\Message;
use Illuminate\Support\Arr;

class MessageSeeder extends BaseSeeder
{
    public function run(): void
    {
        Message::query()->truncate();

        $statuses = MessageStatusEnum::values();

        $customers = Customer::query()->pluck('id')->all();
        $vendorIds = Customer::query()->where('is_vendor', true)->pluck('id')->all();

        $sampleMessages = [
            [
                'name' => 'John Doe',
                'phone' => '123-456-7890',
                'email' => 'johndoe@example.com',
                'content' => 'I would like to know more about your services.',
                'ip_address' => '192.168.1.1',
            ],
            [
                'name' => 'Jane Smith',
                'phone' => '987-654-3210',
                'email' => 'janesmith@example.com',
                'content' => 'Can you provide a quotation for the project?',
                'ip_address' => '192.168.1.2',
            ],
            [
                'name' => 'Alice Brown',
                'phone' => '555-666-7777',
                'email' => 'alicebrown@example.com',
                'content' => 'I am having an issue with my recent order.',
                'ip_address' => '192.168.1.3',
            ],
            [
                'name' => 'Bob Green',
                'phone' => '222-333-4444',
                'email' => 'bobgreen@example.com',
                'content' => 'What are your business hours?',
                'ip_address' => '192.168.1.4',
            ],
            [
                'name' => 'Charlie White',
                'phone' => '333-444-5555',
                'email' => 'charliewhite@example.com',
                'content' => 'I need assistance with your product.',
                'ip_address' => '192.168.1.5',
            ],
            [
                'name' => 'Diana Blue',
                'phone' => '444-555-6666',
                'email' => 'dianablue@example.com',
                'content' => 'Do you offer international shipping?',
                'ip_address' => '192.168.1.6',
            ],
            [
                'name' => 'Edward Black',
                'phone' => '555-666-7778',
                'email' => 'edwardblack@example.com',
                'content' => 'Can I schedule an appointment?',
                'ip_address' => '192.168.1.7',
            ],
            [
                'name' => 'Fiona Gray',
                'phone' => '666-777-8888',
                'email' => 'fionagray@example.com',
                'content' => 'Your website is not loading for me.',
                'ip_address' => '192.168.1.8',
            ],
            [
                'name' => 'George Violet',
                'phone' => '777-888-9999',
                'email' => 'georgeviolet@example.com',
                'content' => 'I want to change my order details.',
                'ip_address' => '192.168.1.9',
            ],
            [
                'name' => 'Hannah Pink',
                'phone' => '888-999-0000',
                'email' => 'hannahpink@example.com',
                'content' => 'Can you send me a catalog of your products?',
                'ip_address' => '192.168.1.10',
            ],
            [
                'name' => 'Ian Orange',
                'phone' => '999-000-1111',
                'email' => 'ianorange@example.com',
                'content' => 'Do you offer discounts for bulk purchases?',
                'ip_address' => '192.168.1.11',
            ],
            [
                'name' => 'Jack Purple',
                'phone' => '000-111-2222',
                'email' => 'jackpurple@example.com',
                'content' => 'I forgot my account password.',
                'ip_address' => '192.168.1.12',
            ],
            [
                'customer_id' => 13,
                'name' => 'Karen Yellow',
                'phone' => '111-222-3333',
                'email' => 'karenyellow@example.com',
                'content' => 'Can you expedite my order?',
                'ip_address' => '192.168.1.13',
                'status' => 'pending',
            ],
            [
                'name' => 'Liam Red',
                'phone' => '222-333-4445',
                'email' => 'liamred@example.com',
                'content' => 'Do you have a return policy?',
                'ip_address' => '192.168.1.14',
            ],
            [
                'name' => 'Mia Silver',
                'phone' => '333-444-5556',
                'email' => 'miasilver@example.com',
                'content' => 'Thank you for resolving my issue quickly.',
                'ip_address' => '192.168.1.15',
            ],
            [
                'name' => 'Noah Gold',
                'phone' => '444-555-6667',
                'email' => 'noahgold@example.com',
                'content' => 'Can I get an invoice for my purchase?',
                'ip_address' => '192.168.1.16',
            ],
            [
                'name' => 'Olivia Bronze',
                'phone' => '555-666-7779',
                'email' => 'oliviabronze@example.com',
                'content' => 'I would like to cancel my order.',
                'ip_address' => '192.168.1.17',
            ],
            [
                'name' => 'Paul Amber',
                'phone' => '666-777-8889',
                'email' => 'paulamber@example.com',
                'content' => 'Your support team is very helpful.',
                'ip_address' => '192.168.1.18',
            ],
            [
                'name' => 'Quinn Platinum',
                'phone' => '777-888-9990',
                'email' => 'quinnplatinum@example.com',
                'content' => 'How do I track my shipment?',
                'ip_address' => '192.168.1.19',
            ],
            [
                'name' => 'Ruby Diamond',
                'phone' => '888-999-0001',
                'email' => 'rubydiamond@example.com',
                'content' => 'Great service and prompt delivery!',
                'ip_address' => '192.168.1.20',
            ],
        ];

        $now = $this->now();

        foreach ($sampleMessages as $index => $message) {
            $message['status'] = Arr::random($statuses);
            $message['customer_id'] = Arr::random($customers);
            $message['vendor_id'] = $index % 3 == 0 ? Arr::random($vendorIds) : null;

            Message::query()->insert([
                'customer_id' => $message['customer_id'],
                'name' => $message['name'],
                'phone' => $message['phone'],
                'email' => $message['email'],
                'content' => $message['content'],
                'ip_address' => $message['ip_address'],
                'vendor_id' => $message['vendor_id'],
                'status' => $message['status'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
