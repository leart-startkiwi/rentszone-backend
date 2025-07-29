<?php

return [
    [
        'name' => 'Car Rentals',
        'flag' => 'car-rentals.index',
    ],

    /**
     * Cars
     */
    [
        'name' => 'Cars',
        'flag' => 'car-rentals.cars.index',
        'parent_flag' => 'car-rentals.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'car-rentals.cars.create',
        'parent_flag' => 'car-rentals.cars.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'car-rentals.cars.edit',
        'parent_flag' => 'car-rentals.cars.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'car-rentals.cars.destroy',
        'parent_flag' => 'car-rentals.cars.index',
    ],

    /**
     * Customers
     */
    [
        'name' => 'Customers',
        'flag' => 'car-rentals.customers.index',
        'parent_flag' => 'car-rentals.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'car-rentals.customers.create',
        'parent_flag' => 'car-rentals.customers.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'car-rentals.customers.edit',
        'parent_flag' => 'car-rentals.customers.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'car-rentals.customers.destroy',
        'parent_flag' => 'car-rentals.customers.index',
    ],

    /**
     * Bookings
     */
    [
        'name' => 'Bookings',
        'flag' => 'car-rentals.bookings.index',
        'parent_flag' => 'car-rentals.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'car-rentals.bookings.create',
        'parent_flag' => 'car-rentals.bookings.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'car-rentals.bookings.edit',
        'parent_flag' => 'car-rentals.bookings.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'car-rentals.bookings.destroy',
        'parent_flag' => 'car-rentals.bookings.index',
    ],

    /**
     * Invoices
     */
    [
        'name' => 'Invoices',
        'flag' => 'car-rentals.invoices.index',
        'parent_flag' => 'car-rentals.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'car-rentals.invoices.create',
        'parent_flag' => 'car-rentals.invoices.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'car-rentals.invoices.edit',
        'parent_flag' => 'car-rentals.invoices.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'car-rentals.invoices.destroy',
        'parent_flag' => 'car-rentals.invoices.index',
    ],

    /**
     * Reviews
     */
    [
        'name' => 'Reviews',
        'flag' => 'car-rentals.reviews.index',
        'parent_flag' => 'car-rentals.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'car-rentals.reviews.edit',
        'parent_flag' => 'car-rentals.reviews.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'car-rentals.reviews.destroy',
        'parent_flag' => 'car-rentals.reviews.index',
    ],

    /**
     * Coupons
     */
    [
        'name' => 'Taxes',
        'flag' => 'car-rentals.coupons.index',
        'parent_flag' => 'car-rentals.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'car-rentals.coupons.create',
        'parent_flag' => 'car-rentals.coupons.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'car-rentals.coupons.edit',
        'parent_flag' => 'car-rentals.coupons.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'car-rentals.coupons.destroy',
        'parent_flag' => 'car-rentals.coupons.index',
    ],

    /**
     * Taxes
     */
    [
        'name' => 'Taxes',
        'flag' => 'car-rentals.taxes.index',
        'parent_flag' => 'car-rentals.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'car-rentals.taxes.create',
        'parent_flag' => 'car-rentals.taxes.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'car-rentals.taxes.edit',
        'parent_flag' => 'car-rentals.taxes.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'car-rentals.taxes.destroy',
        'parent_flag' => 'car-rentals.taxes.index',
    ],

    [
        'name' => 'Car Attributes',
        'flag' => 'car-rentals.attributes.index',
    ],

    /**
     * Car Makes
     */
    [
        'name' => 'Car Makes',
        'flag' => 'car-rentals.car-makes.index',
        'parent_flag' => 'car-rentals.attributes.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'car-rentals.car-makes.create',
        'parent_flag' => 'car-rentals.car-makes.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'car-rentals.car-makes.edit',
        'parent_flag' => 'car-rentals.car-makes.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'car-rentals.car-makes.destroy',
        'parent_flag' => 'car-rentals.car-makes.index',
    ],

    /**
     * Car Types
     */
    [
        'name' => 'Car Types',
        'flag' => 'car-rentals.car-types.index',
        'parent_flag' => 'car-rentals.attributes.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'car-rentals.car-types.create',
        'parent_flag' => 'car-rentals.car-types.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'car-rentals.car-types.edit',
        'parent_flag' => 'car-rentals.car-types.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'car-rentals.car-types.destroy',
        'parent_flag' => 'car-rentals.car-types.index',
    ],

    /**
     * Car Transmissions
     */
    [
        'name' => 'Car Transmissions',
        'flag' => 'car-rentals.car-transmissions.index',
        'parent_flag' => 'car-rentals.attributes.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'car-rentals.car-transmissions.create',
        'parent_flag' => 'car-rentals.car-transmissions.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'car-rentals.car-transmissions.edit',
        'parent_flag' => 'car-rentals.car-transmissions.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'car-rentals.car-transmissions.destroy',
        'parent_flag' => 'car-rentals.car-transmissions.index',
    ],

    /**
     * Car Fuels
     */
    [
        'name' => 'Car Fuels',
        'flag' => 'car-rentals.car-fuels.index',
        'parent_flag' => 'car-rentals.attributes.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'car-rentals.car-fuels.create',
        'parent_flag' => 'car-rentals.car-fuels.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'car-rentals.car-fuels.edit',
        'parent_flag' => 'car-rentals.car-fuels.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'car-rentals.car-fuels.destroy',
        'parent_flag' => 'car-rentals.car-fuels.index',
    ],

    /**
     * Car Colors
     */
    [
        'name' => 'Car Colors',
        'flag' => 'car-rentals.car-colors.index',
        'parent_flag' => 'car-rentals.attributes.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'car-rentals.car-colors.create',
        'parent_flag' => 'car-rentals.car-colors.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'car-rentals.car-colors.edit',
        'parent_flag' => 'car-rentals.car-colors.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'car-rentals.car-colors.destroy',
        'parent_flag' => 'car-rentals.car-colors.index',
    ],

    /**
     * Maintenance History
     */
    [
        'name' => 'Car Maintenance History',
        'flag' => 'car-rentals.car-maintenance-histories.index',
        'parent_flag' => 'car-rentals.attributes.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'car-rentals.car-maintenance-histories.create',
        'parent_flag' => 'car-rentals.car-maintenance-histories.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'car-rentals.car-maintenance-histories.edit',
        'parent_flag' => 'car-rentals.car-maintenance-histories.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'car-rentals.car-maintenance-histories.destroy',
        'parent_flag' => 'car-rentals.car-maintenance-histories.index',
    ],

    /**
     * Car Tags
     */
    [
        'name' => 'Car Tags',
        'flag' => 'car-rentals.car-tags.index',
        'parent_flag' => 'car-rentals.attributes.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'car-rentals.car-tags.create',
        'parent_flag' => 'car-rentals.car-tags.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'car-rentals.car-tags.edit',
        'parent_flag' => 'car-rentals.car-tags.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'car-rentals.car-tags.destroy',
        'parent_flag' => 'car-rentals.car-tags.index',
    ],

    /**
     * Car Categories
     */
    [
        'name' => 'Car Categories',
        'flag' => 'car-rentals.car-categories.index',
        'parent_flag' => 'car-rentals.attributes.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'car-rentals.car-categories.create',
        'parent_flag' => 'car-rentals.car-categories.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'car-rentals.car-categories.edit',
        'parent_flag' => 'car-rentals.car-categories.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'car-rentals.car-categories.destroy',
        'parent_flag' => 'car-rentals.car-categories.index',
    ],

    /**
     * Car Type
     */
    [
        'name' => 'Car Types',
        'flag' => 'car-rentals.car-types.index',
        'parent_flag' => 'car-rentals.attributes.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'car-rentals.car-types.create',
        'parent_flag' => 'car-rentals.car-types.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'car-rentals.car-types.edit',
        'parent_flag' => 'car-rentals.car-types.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'car-rentals.car-types.destroy',
        'parent_flag' => 'car-rentals.car-types.index',
    ],

    /**
     * Car Amenities
     */
    [
        'name' => 'Car Amenities',
        'flag' => 'car-rentals.car-amenities.index',
        'parent_flag' => 'car-rentals.attributes.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'car-rentals.car-amenities.create',
        'parent_flag' => 'car-rentals.car-amenities.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'car-rentals.car-amenities.edit',
        'parent_flag' => 'car-rentals.car-amenities.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'car-rentals.car-amenities.destroy',
        'parent_flag' => 'car-rentals.car-amenities.index',
    ],

    /**
     * Car Transmission
     */
    [
        'name' => 'Car Transmissions',
        'flag' => 'car-rentals.car-transmissions.index',
        'parent_flag' => 'car-rentals.attributes.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'car-rentals.car-transmissions.create',
        'parent_flag' => 'car-rentals.car-transmissions.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'car-rentals.car-transmissions.edit',
        'parent_flag' => 'car-rentals.car-transmissions.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'car-rentals.car-transmissions.destroy',
        'parent_flag' => 'car-rentals.car-transmissions.index',
    ],

    /**
     * Car Fuel Type
     */
    [
        'name' => 'Car Fuels',
        'flag' => 'car-rentals.car-fuels.index',
        'parent_flag' => 'car-rentals.attributes.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'car-rentals.car-fuels.create',
        'parent_flag' => 'car-rentals.car-fuels.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'car-rentals.car-fuels.edit',
        'parent_flag' => 'car-rentals.car-fuels.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'car-rentals.car-fuels.destroy',
        'parent_flag' => 'car-rentals.car-fuels.index',
    ],

    /**
     * Service
     */
    [
        'name' => 'Services',
        'flag' => 'car-rentals.services.index',
        'parent_flag' => 'car-rentals.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'car-rentals.services.create',
        'parent_flag' => 'car-rentals.services.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'car-rentals.services.edit',
        'parent_flag' => 'car-rentals.services.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'car-rentals.services.destroy',
        'parent_flag' => 'car-rentals.services.index',
    ],

    /**
     * Car Address
     */
    [
        'name' => 'Addresses',
        'flag' => 'car-rentals.car-addresses.index',
        'parent_flag' => 'car-rentals.attributes.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'car-rentals.car-addresses.create',
        'parent_flag' => 'car-rentals.car-addresses.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'car-rentals.car-addresses.edit',
        'parent_flag' => 'car-rentals.car-addresses.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'car-rentals.car-addresses.destroy',
        'parent_flag' => 'car-rentals.car-addresses.index',
    ],

    /**
     * Booking Reports
     */
    [
        'name' => 'Booking Reports',
        'flag' => 'car-rentals.booking.reports.index',
        'parent_flag' => 'car-rentals.index',
    ],

    /**
     * Booking Calendar
     */
    [
        'name' => 'Booking Calendar',
        'flag' => 'car-rentals.booking.calendar.index',
        'parent_flag' => 'car-rentals.index',
    ],

    /**
     * Messages
     */
    [
        'name' => 'Messages',
        'flag' => 'car-rentals.message.index',
        'parent_flag' => 'car-rentals.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'car-rentals.message.edit',
        'parent_flag' => 'car-rentals.message.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'car-rentals.message.destroy',
        'parent_flag' => 'car-rentals.message.index',
    ],
];
