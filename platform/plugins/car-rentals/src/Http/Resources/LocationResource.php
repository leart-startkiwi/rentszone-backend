<?php

namespace Botble\CarRentals\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->getKey(),
            'name' => implode(', ', array_filter([$this->name, $this->state?->name])),
        ];
    }
}
