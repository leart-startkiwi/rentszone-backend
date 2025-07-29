<?php

namespace Botble\CarRentals\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Models\BaseModel;
use Botble\Base\Supports\Avatar;
use Botble\CarRentals\Enums\MessageStatusEnum;
use Botble\CarRentals\Models\Builders\MessageBuilder;
use Botble\Media\Facades\RvMedia;
use Exception;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends BaseModel
{
    protected $table = 'cr_messages';

    protected $fillable = [
        'customer_id',
        'name',
        'phone',
        'email',
        'content',
        'ip_address',
        'status',
    ];

    protected $casts = [
        'status' => MessageStatusEnum::class,
        'name' => SafeContent::class,
        'content' => SafeContent::class,
    ];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                try {
                    return (new Avatar())->create($this->name)->toBase64();
                } catch (Exception) {
                    return RvMedia::getDefaultImage();
                }
            },
        );
    }

    public function newEloquentBuilder($query): MessageBuilder
    {
        return new MessageBuilder($query);
    }
}
