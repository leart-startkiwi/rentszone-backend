<?php

namespace Botble\CarRentals\Models\Scopes;

use Botble\Base\Facades\AdminHelper;
use Botble\CarRentals\Enums\ModerationStatusEnum;
use Botble\CarRentals\Facades\CarRentalsHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\App;

class ApprovedCarScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (AdminHelper::isInAdmin() || App::runningInConsole()) {
            return;
        }

        $enabledPostApproval = CarRentalsHelper::isEnabledPostApproval();

        if ($enabledPostApproval) {
            $builder->where(function ($query): void {
                $query->where('moderation_status', ModerationStatusEnum::APPROVED);
            });
        }
    }
}
