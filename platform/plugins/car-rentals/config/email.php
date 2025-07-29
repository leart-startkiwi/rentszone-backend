<?php

return [
    'name' => 'Car Rentals',
    'description' => 'Config email templates for Car Rentals',
    'templates' => array_filter([
        'confirm-email' => [
            'title' => 'Confirm email',
            'description' => 'Send email to user when they register an account to verify their email',
            'subject' => 'Confirm Email Notification',
            'can_off' => false,
            'variables' => [
                'verify_link' => 'Verify email link',
                'customer_name' => 'Customer name',
            ],
        ],
        'password-reminder' => [
            'title' => 'Reset password',
            'description' => 'Send email to user when requesting reset password',
            'subject' => 'Reset Password',
            'can_off' => false,
            'variables' => [
                'reset_link' => 'Reset password link',
                'customer_name' => 'Customer name',
            ],
        ],
        'booking-confirm' => [
            'title' => 'plugins/car-rentals::settings.email.templates.booking_confirm.booking_confirmation_title',
            'description' => 'plugins/car-rentals::settings.email.templates.booking_confirm.booking_confirmation_description',
            'subject' => 'Car Rental Booking Confirmation',
            'can_off' => true,
            'variables' => [
                'booking_code' => 'plugins/car-rentals::settings.email.templates.booking_confirm.booking_code',
                'customer_name' => 'plugins/car-rentals::settings.email.templates.booking_confirm.customer_name',
                'customer_phone' => 'plugins/car-rentals::settings.email.templates.booking_confirm.customer_phone',
                'customer_email' => 'plugins/car-rentals::settings.email.templates.booking_confirm.customer_email',
                'payment_method' => 'plugins/car-rentals::settings.email.templates.booking_confirm.payment_method',
                'car_name' => 'plugins/car-rentals::settings.email.templates.booking_confirm.car_name',
                'pickup_address' => 'plugins/car-rentals::settings.email.templates.booking_confirm.pickup_address',
                'return_address' => 'plugins/car-rentals::settings.email.templates.booking_confirm.return_address',
                'rental_start_date' => 'plugins/car-rentals::settings.email.templates.booking_confirm.rental_start_date',
                'rental_end_date' => 'plugins/car-rentals::settings.email.templates.booking_confirm.rental_end_date',
                'note' => 'plugins/car-rentals::settings.email.templates.booking_confirm.note',
            ],
        ],
        'message' => [
            'title' => 'New message',
            'description' => 'Send to the seller/car owner email / admin email when someone contact via message form',
            'subject' => 'New message',
            'can_off' => true,
            'variables' => [
                'message_name' => 'Name',
                'message_phone' => 'Phone',
                'message_email' => 'Email',
                'message_content' => 'Content',
                'message_link' => 'Link',
                'message_subject' => 'Subject',
                'message_ip_address' => 'IP address',
                'message_custom_fields' => 'Custom fields',
            ],
        ],
        'new-pending-car' => [
            'title' => 'New pending car',
            'description' => 'Send email to admin when a new car created',
            'subject' => 'New pending car by {{ post_author }} waiting for approve',
            'can_off' => true,
            'enabled' => false,
            'variables' => [
                'post_author' => 'Post Author',
                'post_name' => 'Post Name',
                'post_url' => 'Post URL',
            ],
        ],
        'booking-notice-to-admin' => [
            'title' => 'plugins/car-rentals::settings.email.templates.booking_notice_title',
            'description' => 'plugins/car-rentals::settings.email.templates.booking_notice_description',
            'subject' => 'New Booking from {{ site_title }}',
            'can_off' => true,
        ],
        'booking-status-changed' => [
            'title' => 'plugins/car-rentals::settings.email.templates.booking_status_changed_title',
            'description' => 'plugins/car-rentals::settings.email.templates.booking_status_changed_description',
            'subject' => 'Your Booking Has Been Updated!',
            'can_off' => true,
        ],
    ]),
];
