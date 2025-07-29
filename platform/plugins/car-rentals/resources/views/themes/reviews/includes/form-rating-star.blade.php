<div class="box-type-reviews">
    <form>
        <div class="d-flex align-items-center mb-3">
            <div class="form-rating-stars ms-2">
                @for ($i = 5; $i >= 1; $i--)
                    <input
                        class="btn-check"
                        id="rating-star-{{ $i }}"
                        name="star"
                        type="radio"
                        value="{{ $i }}"
                        @checked($i === 5)
                    >
                    <label for="rating-star-{{ $i }}" title="{{ $i }} stars">
                        <x-core::icon name="ti ti-star-filled" />
                    </label>
                @endfor
            </div>
        </div>
    </form>
</div>
