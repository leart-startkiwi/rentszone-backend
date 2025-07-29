<?php

namespace Botble\CarRentals\Events;

use Botble\Base\Events\Event;
use Botble\CarRentals\Models\Customer;
use Botble\CarRentals\Models\Withdrawal;
use Illuminate\Queue\SerializesModels;

class WithdrawalRequested extends Event
{
    use SerializesModels;

    public function __construct(public Customer $customer, public Withdrawal $withdrawal)
    {
    }
}
