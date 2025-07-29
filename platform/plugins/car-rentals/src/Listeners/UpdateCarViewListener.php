<?php

namespace Botble\CarRentals\Listeners;

use Botble\CarRentals\Events\CarViewed;
use Botble\CarRentals\Models\CarView;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Throwable;

class UpdateCarViewListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(CarViewed $event): void
    {
        try {
            CarView::query()
                ->upsert(
                    [
                        'car_id' => $event->car->getKey(),
                        'date' => $event->dateTime->toDateString(),
                        'views' => 1,
                    ],
                    ['car_id', 'date'],
                    ['views' => DB::raw('views + 1')],
                );
        } catch (Throwable $exception) {
            info($exception->getMessage());
        }
    }
}
