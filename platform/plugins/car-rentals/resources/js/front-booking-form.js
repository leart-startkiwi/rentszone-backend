$(document).ready(function() {
    $(document).on('change', '.booking-form input, .booking-form select', function() {
        calculateTotal()
    })

    function calculateTotal() {
        const form = $('.booking-form').find('form')
        const data = form.serialize()

        $.ajax({
            url: form.attr('data-estimate-url'),
            method: 'POST',
            data: data,
            success: function(response) {
                form.find('.pricing-summary').html(response.data)
            }
        })
    }
})
