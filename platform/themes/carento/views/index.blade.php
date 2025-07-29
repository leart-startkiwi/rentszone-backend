@php
    Theme::set('breadcrumbEnabled', false);
@endphp


<div class="container">
    <div class="mt-8">
        <h4 class="text-danger">You need to setup your homepage first!</h4>

        <p><strong>1. Go to Admin -> Plugins then activate all plugins.</strong></p>
        <p><strong>2. Go to Admin -> Pages and create a page:</strong></p>

        <div class="mt-2">
            <div>- Content:</div>
            <pre class="my-2 bg-dark text-light p-2 rounded"><code>[hero-banner title="Looking for a vehicle? &lt;br class=“d-none d-lg-block” /&gt;You’re in the perfect spot." subtitle="Find Your Perfect Car" background_image="backgrounds/hero-banner.png" content_1="High quality at a low cost." content_2="Premium services" content_3="24/7 roadside support." quantity="3"][/hero-banner]
[car-advance-search button_search_name="Find a Vehicle" link_need_help="/faqs" top="-124" bottom="0" left="0" right="0" url="/car-list-1" background_color="rgb(242, 244, 246)"][/car-advance-search]
[brands style="style-1" title="Premium Brands" subtitle="Unveil the Finest Selection of High-End Vehicles" brand_ids="" button_label="Show All Brands" button_url="/cars"][/brands]
[cars style="style-latest" title="Most View Vehicles" subtitle="The world's leading car brands" number_rows="2" limit="12" button_label="View More" button_url="/car-list-1"][/cars]
[intro-video title="Receive a Competitive Offer Sell Your Car to Us Today." description="We are committed to delivering exceptional service, competitive pricing, and a diverse selection of options for our customers." subtitle="Best Car Rental System" youtube_video_url="https://www.youtube.com/watch?v=ldusxyoq0Y8" image="intro-video/1.png" content_1="Expert Certified Mechanics" content_2="First Class Services" content_3="Get Reasonable Price" content_4="24/7 road assistance" content_5="Genuine Spares Parts" content_6="Free Pick-Up &amp; Drop-Offs" quantity="6"][/intro-video]
[car-types title="Browse by Type" sub_title="Find the perfect ride for any occasion" car_types="" redirect_url="car-list-1" button_label="View More" button_url="/cars"][/car-types]
[why-us sub_title="HOW IT WORKS" title="Presenting Your New Go-To Car &lt;br&gt; Rental Experience" card_image_1="icons/car-location.png" card_title_1="Choose a Location" card_content_1="Select the ideal destination to begin your journey with ease" card_image_2="icons/car-selected.png" card_title_2="Choose Your Vehicle" card_content_2="Browse our fleet and find the perfect car for your needs" card_image_3="icons/car.png" card_title_3="Verification" card_content_3="Review your information and confirm your booking" card_image_4="icons/car-key.png" card_title_4="Begin Your Journey" card_content_4="Start your adventure with confidence and ease" quantity="4"][/why-us]
[cars style="style-feature" title="Featured Listings" subtitle="Find the perfect ride for any occasion" limit="4" button_label="View More" button_url="/car-list-4"][/cars]
[featured-block title="Get a great deal for your vehicle sell to us now" subtitle="Trusted Expertise" description="Get the best value for your vehicle with our transparent and straightforward selling process" button_label="Get Started Now" button_url="/car-list-1" image_1="featured-block/img-1.png" image_2="featured-block/img-2.png" image_3="featured-block/img-3.png" image_4="featured-block/img-4.png" image_5="featured-block/img-5.png" content_1="Experienced Professionals You Can Trust" content_2="Clear and Transparent Pricing, No Hidden Fees" content_3="Genuine Spares Parts" quantity="3"][/featured-block]
[our-services title="Our Services" main_content="Serving You with Quality, Comfort, and Convenience" button_label="View More" button_url="/services" name_1="Venice" description_1="356 Properties" image_1="our-services/img-1.png" link_1="/services/driver-rental-service" name_2="New York" description_2="356 Properties" image_2="our-services/img-2.png" link_2="/services/oil-change-service" name_3="Budapest" description_3="356 Properties" image_3="our-services/img-3.png" link_3="/services/car-wash-detailing-package" name_4="New York" description_4="356 Properties" image_4="our-services/img-4.png" link_4="/services/roadside-assistance" name_5="Amsterdam" description_5="356 Properties" image_5="our-services/img-1.png" link_5="/services/tire-replacement-balancing" quantity="5"][/our-services]
[simple-banners title_1="Looking for a rental car?" subtitle_1="Discover your ideal rental car for every adventure, &lt;br&gt;whether it's a road trip or business travel" image_1="simple-banners/img-1.png" button_url_1="/car-list-3" button_name_1="Get Started Now" button_color_1="#70f46d" background_color_1="#9dd3fb" title_2="Loking for a rental car?" subtitle_2="Maximize your vehicle's potential: seamlessly &lt;br&gt; rent or sell with confidence" image_2="simple-banners/img-2.png" button_url_2="/car-list-3" button_name_2="Get Started Now" button_color_2="#ffffff" background_color_2="#ffec88" quantity="2"][/simple-banners]
[testimonials title="What they say about us?" subtitle="Testimonials" testimonial_ids="1,2,3,4"][/testimonials]
[blog-posts style="style-3" title="Car Reviews" subtitle="Expert insights and honest evaluations to help you choose the perfect car" link_label="View More" link_url="/blog" category_ids="" limit="4"][/blog-posts]
[blog-posts style="style-1" title="Upcoming Cars &amp; Events" subtitle="Stay ahead with the latest car releases and upcoming events" button_label="Keep Reading" category_ids="" limit="10"][/blog-posts]
[install-apps title="Carento App is Available" description="Install App" apps_description="Manage all your car rentals on the go with the Carento app" android_app_url="/contact" android_app_image="install-apps/googleplay.png" ios_app_url="/contact" ios_app_image="install-apps/appstore.png" decor_image="install-apps/truck.png"][/install-apps]</code></pre>
            <br>
            <div>- Template: <strong>Default</strong>.</div>
        </div>

        <p><strong>3. Then go to Admin -> Appearance -> Theme options -> Page to set your homepage.</strong></p>
    </div>
</div>
