'use strict';

$(document).ready(function () {
    // Trigger search on page load if dates are already set
    if ($('#rental_start_date').val() && $('#rental_end_date').val()) {
        setTimeout(function() {
            $('#search_cars_button').trigger('click');
        }, 500);
    }

    // Handle car search button click
    $(document).on('click', '#search_cars_button', function (event) {
        event.preventDefault();
        event.stopPropagation();
        const startDate = $('#rental_start_date').val();
        const endDate = $('#rental_end_date').val();

        if (!startDate || !endDate) {
            Botble.showError('Please select both start date and end date to search for available cars.');
            return;
        }

        // Show loading indicator
        $('#search_cars_button').addClass('button-loading');
        $('#car_cards_container').html('');

        // Use Ajax to search for cars
        $.ajax({
            url: route('car-rentals.bookings.search-cars'),
            type: 'GET',
            data: {
                rental_start_date: startDate,
                rental_end_date: endDate
            },
            success: function(res) {
                $('#search_cars_button').removeClass('button-loading');

                if (res.error) {
                    Botble.showError(res.message);
                } else {
                    // Display car cards
                    if (res.data.cars && res.data.cars.length > 0) {
                        $('#car_cards_container').html(res.data.html);
                        $('#booking_form_container').removeClass('d-none');
                    } else {
                        $('#car_cards_container').html('<div class="alert alert-warning">No cars available for the selected dates</div>');
                    }
                }
            },
            error: function(error) {
                $('#search_cars_button').removeClass('button-loading');
                Botble.handleError(error);
            }
        });
    });

    // Handle car selection
    $(document).on('click', '.select-car-button', function() {
        const carId = $(this).data('id');
        const carName = $(this).data('name');

        // Update hidden input and display selected car name
        $('#car_id').val(carId);
        $('#selected_car_name').text(carName);

        // Show booking details section
        $('#booking_details_container').removeClass('d-none');

        // Hide warning and show selected car info
        $('#car_selection_warning').addClass('d-none');
        $('#selected_car_info').removeClass('d-none');

        // Highlight selected car and unhighlight others
        $('.car-card').removeClass('selected-car');
        $(this).closest('.car-card').addClass('selected-car');

        // Scroll to booking details
        $('html, body').animate({
            scrollTop: $('#booking_details_container').offset().top - 100
        }, 500);
    });
});
