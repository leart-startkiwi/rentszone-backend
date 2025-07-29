<?php

namespace Botble\CarRentals\Models\Builders;

use Botble\Base\Models\BaseQueryBuilder;
use Botble\CarRentals\Models\Customer;
use Illuminate\Database\Eloquent\Builder;

class MessageBuilder extends BaseQueryBuilder
{
    public function whereCustomer(Customer $customer): static
    {
        return $this
            ->whereHas('car', function (Builder $query) use ($customer): void {
                $query
                    ->where('author_type', $customer::class)
                    ->where('author_id', $customer->getKey());
            });
    }
}
