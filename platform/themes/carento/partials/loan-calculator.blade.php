{{ __('Apply Car Loan') }}

- {{ __('Vehicle Price: :price', ['price' => format_price($vehiclePrice, $currency)]) }}
- {{ __('Annual Interest Rate: :rate', ['rate' => $annualInterestRate . '%']) }}
- {{ __('Loan Term: :term', ['term' => $loanTerm . ' months (' . $loanTerm / 12 . ' years)']) }}
- {{ __('Down Payment: :payment', ['payment' => format_price($downPayment, $currency)]) }}
