<?php

namespace Botble\CarRentals\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasActiveCarsRelation
{
    public function activeCars(): BelongsToMany|HasMany|MorphMany
    {
        return $this->cars()->active(); // @phpstan-ignore-line
    }

    public function activeCar(): BelongsTo|HasOne|MorphOne
    {
        return $this->car()->active(); // @phpstan-ignore-line
    }
}
