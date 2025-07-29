$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
    })

    const $carLoanForm = $('.car-loan-form')

    if ($carLoanForm.length) {
        calculate()
    }

    $(document).on('keyup', '.car-loan-form input', function() {
        debouncedChangeInput()
    })

    $(document).on('change', '.car-loan-form select', function() {
        calculate()
    })

    const debouncedChangeInput = debounce(calculate, 500);

    function calculate () {
        const form = $('.car-loan-form')
        $.ajax({
            url: form.attr('data-url'),
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                const $result = $('.loan-form-result')
                $('.loan-form-error').hide()

                $result.find('.down-payment-amount').html(response.down_payment)
                $result.find('.est-monthly-payment').html(response.monthly_payment)
                $result.find('.loan-amount').html(response.loan_amount)
            },
            error: function(error) {
                $('.loan-form-error').html(error.responseJSON.message).show()
            }
        })
    }

    function debounce(func, wait) {
        let timeout;

        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };

            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
})
