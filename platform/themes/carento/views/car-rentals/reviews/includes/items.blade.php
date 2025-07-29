<div class="list-reviews">
    @foreach($reviews as $review)
        <div class="item-review">
            <div class="head-review">
                @if($customer = $review->customer)
                    <div class="author-review">
                        {{ RvMedia::image($customer->avatar_url, $customer->name, 'thumb') }}

                        <div class="author-info">
                            <p class="text-lg-bold">{{ $customer->name }}</p>
                            <p class="text-sm-medium neutral-500">{{ $review->created_at->format('F j, Y \a\t g:i a') }}</p>
                        </div>
                    </div>
                @endif

                <div class="rate-review">
                    @include(CarRentalsHelper::viewPath('reviews.includes.rating-star'), ['avg' => $review->star])
                </div>
            </div>
            <div class="content-review">
                <p class="text-sm-medium neutral-800">{!! BaseHelper::clean($review->content) !!}</p>
            </div>
        </div>
    @endforeach

</div>
