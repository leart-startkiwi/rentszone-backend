<?php

namespace Botble\CarRentals\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Base\Supports\Avatar;
use Botble\CarRentals\Notifications\ConfirmEmailNotification;
use Botble\CarRentals\Notifications\ResetPasswordNotification;
use Botble\Media\Facades\RvMedia;
use Exception;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class Customer extends BaseModel implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable;
    use Authorizable;
    use CanResetPassword;
    use MustVerifyEmail;
    use Notifiable;
    use HasApiTokens;

    protected $table = 'cr_customers';

    protected $fillable = [
        'name',
        'email',
        'avatar',
        'phone',
        'dob',
        'address',
        'status',
        'remember_token',
        'password',
        'balance',
        'bank_info',
        'payout_payment_method',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'dob' => 'date',
        'password' => 'hashed',
        'status' => BaseStatusEnum::class,
        'bank_info' => 'array',
    ];

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new ConfirmEmailNotification());
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(CarReview::class, 'customer_id');
    }

    protected function avatarUrl(): Attribute
    {
        return Attribute::get(function () {
            if ($this->avatar) {
                return RvMedia::getImageUrl($this->avatar, 'thumb');
            }

            if ($defaultAvatar = setting('car_rentals_customer_default_avatar')) {
                return RvMedia::getImageUrl($defaultAvatar);
            }

            try {
                return (new Avatar())->create(Str::ucfirst($this->name))->toBase64();
            } catch (Exception) {
                return RvMedia::getDefaultImage();
            }
        });
    }

    protected function uploadFolder(): Attribute
    {
        return Attribute::make(
            get: function () {
                $folder = 'customers/' . $this->getKey();

                return apply_filters('car_rentals_customers_upload_folder', $folder, $this);
            }
        );
    }

    public function revenues(): HasMany
    {
        return $this->hasMany(Revenue::class, 'customer_id');
    }

    protected static function booted(): void
    {
        static::deleting(function (Customer $customer): void {
            $folder = Storage::path($customer->upload_folder);
            if (File::isDirectory($folder) && Str::endsWith($customer->upload_folder, '/' . $customer->username)) {
                File::deleteDirectory($folder);
            }
        });
    }
}
