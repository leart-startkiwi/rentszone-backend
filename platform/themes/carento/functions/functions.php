<?php

use Botble\Base\Forms\FieldOptions\ColorFieldOption;
use Botble\Base\Forms\FieldOptions\EditorFieldOption;
use Botble\Base\Forms\FieldOptions\HtmlFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\ColorField;
use Botble\Base\Forms\Fields\EditorField;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Blog\Forms\PostForm;
use Botble\CarRentals\Forms\CarForm;
use Botble\CarRentals\Forms\CarMakeForm;
use Botble\CarRentals\Forms\CustomerForm;
use Botble\CarRentals\Forms\Fronts\BookingForm;
use Botble\CarRentals\Models\Currency;
use Botble\Contact\Forms\Fronts\ContactForm;
use Botble\Faq\Forms\FaqCategoryForm;
use Botble\Media\Facades\RvMedia;
use Botble\Newsletter\Facades\Newsletter;
use Botble\Newsletter\Forms\Fronts\NewsletterForm;
use Botble\Page\Forms\PageForm;
use Botble\SimpleSlider\Forms\SimpleSliderForm;
use Botble\SimpleSlider\Forms\SimpleSliderItemForm;
use Botble\SimpleSlider\Models\SimpleSlider;
use Botble\Testimonial\Forms\TestimonialForm;
use Botble\Testimonial\Http\Requests\TestimonialRequest;
use Botble\Theme\Facades\Theme;
use Botble\Theme\Supports\ThemeSupport;
use Botble\Theme\Typography\TypographyItem;
use Illuminate\Http\Request;

register_page_template([
    'default' => __('Default'),
    'homepage' => __('Homepage'),
    'full-width' => __('Full width'),
    'blog-with-sidebar' => __('Blog with sidebar'),
    'blog-without-sidebar' => __('Blog without sidebar'),
]);

app()->booted(function (): void {
    RvMedia::addSize('medium', 800, 800);

    ThemeSupport::registerSiteCopyright();
    ThemeSupport::registerToastNotification();
    ThemeSupport::registerSiteLogoHeight();
    ThemeSupport::registerSocialLinks();
    ThemeSupport::registerSocialSharing();
    ThemeSupport::registerPreloader();
    ThemeSupport::registerLazyLoadImages();

    if (is_plugin_active('newsletter')) {
        Newsletter::registerNewsletterPopup();

        NewsletterForm::extend(function (NewsletterForm $form) {
            return $form->formClass('newsletter-form');
        });
    }

    add_filter('theme_preloader_versions', function (): array {
        return [
            'v2' => __('Default'),
            'v1' => __('Simplify'),
        ];
    }, 128);

    add_filter('theme_preloader', function (string $preloader): string {
        if (theme_option('preloader_version', 'v2') === 'v2') {
            return Theme::partial('preloader');
        }

        return $preloader;
    }, 128);

    Theme::typography()
        ->registerFontFamilies([
            new TypographyItem('primary', __('Primary'), 'Urbanist'),
            new TypographyItem('heading', __('Heading'), 'Urbanist'),
        ])
        ->registerFontSizes([
            new TypographyItem('h1', __('Heading 1'), 64),
            new TypographyItem('h2', __('Heading 2'), 52),
            new TypographyItem('h3', __('Heading 3'), 44),
            new TypographyItem('h4', __('Heading 4'), 36),
            new TypographyItem('h5', __('Heading 5'), 28),
            new TypographyItem('h6', __('Heading 6'), 24),
            new TypographyItem('body', __('Body'), 14),
        ]);

    register_sidebar([
        'id' => 'top_footer_sidebar',
        'name' => __('Top footer sidebar'),
        'description' => __('Widget area displayed at the top section of the footer'),
    ]);

    register_sidebar([
        'id' => 'footer_sidebar',
        'name' => __('Footer sidebar'),
        'description' => __('Widget area displayed in the main footer section'),
    ]);

    register_sidebar([
        'id' => 'bottom_footer_sidebar',
        'name' => __('Bottom footer sidebar'),
        'description' => __('Widget area displayed at the bottom section of the footer'),
    ]);

    register_sidebar([
        'id' => 'header_top_sidebar',
        'name' => __('Header top sidebar'),
        'description' => __('Widget area displayed at the top section of the header'),
    ]);

    register_sidebar([
        'id' => 'above_blog_list_sidebar',
        'name' => __('Above blog list sidebar'),
        'description' => __('Widget area displayed above the blog post listings'),
    ]);

    register_sidebar([
        'id' => 'blog_sidebar',
        'name' => __('Blog sidebar'),
        'description' => __('Widget area displayed alongside blog posts and archives'),
    ]);

    register_sidebar([
        'id' => 'off_canvas_sidebar',
        'name' => __('Off canvas sidebar'),
        'description' => __('Widget area that slides in when triggered by the sidebar button'),
    ]);

    RvMedia::addSize('small-rectangle', 320, 240)
        ->addSize('small-rectangle-vertical', 240, 320)
        ->addSize('medium-square', 520, 520)
        ->addSize('medium-rectangle', 520, 380)
        ->addSize('large-rectangle', 1920, 1080);

    PageForm::extend(function (PageForm $form): void {
        $form
            ->addAfter(
                'image',
                'breadcrumb_background_image',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->metadata()
                    ->label(__('Breadcrumb background image'))
            )
            ->add(
                'breadcrumb_simple',
                OnOffField::class,
                OnOffFieldOption::make()
                    ->metadata()
                    ->defaultValue(false)
                    ->label(__('Simple breadcrumb'))
            )
            ->add(
                'breadcrumb_text_color',
                ColorField::class,
                ColorFieldOption::make()
                    ->metadata()
                    ->label(__('Breadcrumb text color'))
            )
            ->add(
                'breadcrumb_background_color',
                ColorField::class,
                ColorFieldOption::make()
                    ->metadata()
                    ->label(__('Breadcrumb background color'))
            )
            ->add(
                'breadcrumb_display_last_update',
                OnOffField::class,
                OnOffFieldOption::make()
                    ->metadata()
                    ->defaultValue(false)
                    ->label(__('Show last update in breadcrumb?'))
            );
    });

    if (is_plugin_active('faq')) {
        FaqCategoryForm::extend(function (FaqCategoryForm $form) {
            return $form
                ->addAfter(
                    'order',
                    'logo',
                    MediaImageField::class,
                    MediaImageFieldOption::make()
                        ->metadata()
                        ->label(__('Logo'))
                )
                ->addAfter(
                    'logo',
                    'logo_dark',
                    MediaImageField::class,
                    MediaImageFieldOption::make()
                        ->metadata()
                        ->label(__('Logo Dark'))
                );
        });
    }

    if (is_plugin_active('car-rentals')) {
        CarMakeForm::extend(function (CarMakeForm $form) {
            return $form
                ->addAfter(
                    'logo',
                    'logo_dark',
                    MediaImageField::class,
                    MediaImageFieldOption::make()
                        ->metadata()
                        ->label(__('Logo Dark'))
                )
                ->addAfter(
                    'logo_dark',
                    'logo_invert',
                    MediaImageField::class,
                    MediaImageFieldOption::make()
                        ->metadata()
                        ->label(__('Logo Invert'))
                );
        });

        CustomerForm::extend(function (CustomerForm $form) {
            return $form
                ->addAfter(
                    'dob',
                    'place',
                    TextField::class,
                    TextFieldOption::make()
                        ->metadata()
                        ->label(__('Place'))
                        ->placeholder('e.g: Tokyo')
                );
        });

        BookingForm::extend(function (BookingForm $form) {
            return $form
                ->remove('rental_start_date')
                ->remove('rental_end_date')
                ->addAfter(
                    'car_id',
                    'rental_start_html',
                    HtmlField::class,
                    HtmlFieldOption::make()
                        ->view(Theme::getThemeNamespace('partials.booking.partials.rental-date-inputs'))
                );
        });

        CarForm::extend(function (CarForm $form): void {
            $form->addAfter(
                'images[]',
                'youtube_video_url',
                TextField::class,
                TextFieldOption::make()
                    ->metadata()
                    ->label(__('Video URL'))
                    ->colspan(2)
                    ->placeholder('e.g: https://www.youtube.com/watch?v=video_id')
            );
        });
    }

    if (is_plugin_active('testimonial')) {
        add_filter('core_request_rules', function (array $rules, Request $request) {
            if ($request instanceof TestimonialRequest) {
                return array_merge($rules, [
                    'rating_star' => ['required', 'numeric', 'min:1', 'max:5'],
                ]);
            }

            return $rules;
        }, 120, 2);

        TestimonialForm::extend(function (TestimonialForm $form) {
            return $form
                ->addAfter(
                    'company',
                    'rating_star',
                    NumberField::class,
                    NumberFieldOption::make()
                        ->attributes(['min' => 1, 'max' => 5])
                        ->metadata()
                        ->label(__('Rating star'))
                );
        });
    }

    if (is_plugin_active('contact')) {
        ContactForm::extend(function (ContactForm $form) {
            if (
                request()->query('vehicle_price')
                && request()->query('annual_interest_rate')
                && request()->query('loan_term')
                && request()->query('down_payment')
            ) {
                $content = Theme::partial('loan-calculator', [
                    'vehiclePrice' => request()->query('vehicle_price'),
                    'annualInterestRate' => request()->query('annual_interest_rate'),
                    'loanTerm' => request()->query('loan_term'),
                    'downPayment' => request()->query('down_payment'),
                    'currency' => is_plugin_active('car-rentals')
                        ? Currency::query()->where('title', request()->query('currency'))->first()
                        : request()->query('currency'),
                ]);

                return $form
                    ->remove('content')
                    ->addBefore(
                        'filters_after_form',
                        'content',
                        TextareaField::class,
                        TextareaFieldOption::make()
                            ->value($content)
                            ->required()
                            ->label(__('Content'))
                            ->placeholder(__('Write your message here'))
                            ->rows(5)
                            ->maxLength(-1)
                    );
            }

            return $form;
        });
    }

    if (is_plugin_active('simple-slider')) {
        SimpleSliderForm::extend(function (SimpleSliderForm $form): void {
            $form
                ->addAfter(
                    'status',
                    'appearance',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->metadata()
                        ->choices(get_simple_slider_styles())
                        ->label(__('Appearance'))
                )
                ->addAfter(
                    'description',
                    'content_on_top',
                    EditorField::class,
                    EditorFieldOption::make()
                        ->metadata()
                        ->allowedShortcodes()
                        ->label(__('Content On Top'))
                )
                ->addAfter(
                    'content_on_top',
                    'footer_on_top',
                    EditorField::class,
                    EditorFieldOption::make()
                        ->metadata()
                        ->label(__('Footer On Top'))
                );
        });

        SimpleSliderItemForm::extend(function (SimpleSliderItemForm $form) {
            $simpleSliderId = $form->getModel()->simple_slider_id;
            if (empty($simpleSliderId)) {
                $simpleSliderId = $form->getRequest()->integer('simple_slider_id');
            }

            /**
             * @var SimpleSlider|null $simpleSlider
             */
            $simpleSlider = SimpleSlider::query()->find($simpleSliderId);
            if (empty($simpleSlider)) {
                return $form;
            }

            $appearance = $simpleSlider->getMetaData('appearance', true);

            return match ($appearance) {
                'style-1' => $form
                    ->addAfter(
                        'title',
                        'label_top',
                        TextField::class,
                        TextFieldOption::make()
                            ->metadata()
                            ->label(__('Label Top'))
                    ),
                'style-2' => $form
                    ->addAfter(
                        'title',
                        'subtitle',
                        TextareaField::class,
                        TextareaFieldOption::make()
                            ->metadata()
                            ->label(__('Subtitle'))
                    )
                    ->addBefore(
                        'link',
                        'link_label',
                        TextField::class,
                        TextFieldOption::make()
                            ->metadata()
                            ->label(__('Link Label'))
                    ),
                default => $form
            };
        }, 127);
    }

    if (is_plugin_active('blog')) {
        PostForm::extend(function (PostForm $form) {
            $form->addAfter(
                'description',
                'youtube_video_url',
                TextField::class,
                TextFieldOption::make()
                    ->metadata()
                    ->label(__('YouTube Video URL'))
                    ->placeholder(__('Enter Video URL, e.g: https://www.youtube.com/watch?v=xxxxxx'))
            );
        });
    }

    if (! function_exists('get_simple_slider_styles')) {
        function get_simple_slider_styles(): array
        {
            return [
                'style-1' => __('Layout Full Width'),
                'style-2' => __('Layout Center'),
            ];
        }
    }

    if (! function_exists('get_list_of_car_styles')) {
        function get_list_of_car_styles(): array
        {
            return [
                'style-feature' => __('Style Feature'),
                'style-popular' => __('Style Popular'),
                'style-latest' => __('Style Latest'),
            ];
        }
    }

    add_filter('ads_locations', function (array $locations) {
        return [
            ...$locations,
            'footer_before' => __('Footer (before)'),
            'footer_after' => __('Footer (after)'),
            'post_list_before' => __('Post List (before)'),
            'post_list_after' => __('Post List (after)'),
            'post_before' => __('Post Detail (before)'),
            'post_after' => __('Post Detail (after)'),
            'car_list_before' => __('Car List (before)'),
            'car_list_after' => __('Car List (after)'),
            'car_before' => __('Car Detail (before)'),
            'car_after' => __('Car Detail (after)'),
        ];
    }, 128);

    add_filter('cms_installer_themes', function () {
        return [
            'home1' => [
                'label' => 'Home 1',
                'image' => Theme::asset()->url('images/demos/home-1.png'),
            ],
            'home2' => [
                'label' => 'Home 2',
                'image' => Theme::asset()->url('images/demos/home-2.png'),
            ],
            'home3' => [
                'label' => 'Home 3',
                'image' => Theme::asset()->url('images/demos/home-3.png'),
            ],
        ];
    }, 10);
});
