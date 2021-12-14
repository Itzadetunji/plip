<?php

namespace App\Models;

use App\Traits\Uuid;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Propaganistas\LaravelPhone\PhoneNumber;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Propaganistas\LaravelPhone\Casts\RawPhoneNumberCast;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use Uuid;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'nick_name',
        'email',
        'email_verified_at',
        'phone',
        'phone_country',
        'password',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'phone' => RawPhoneNumberCast::class . ':phone_country',
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ["full_name"];

    /**
     * The channels the user receives notification broadcasts on.
     *
     * @return string
     */
    public function receivesBroadcastNotificationsOn()
    {
        return 'users.' . $this->id;
    }

    public function getAvatarAttribute($value)
    {
        if (!empty($value)) {
            return url($value);
        }

        return $value;
    }

    public function getFullNameAttribute()
    {

        return "{$this->first_name} {$this->last_name}";
    }

    public function getPhoneAttribute($value)
    {
        if (!empty($value)) {
           return (string) PhoneNumber::make($value, $this->phone_country);
        }

        return $value;
    }

    public function wallets(): HasMany
    {
        return $this->hasMany(UserWallet::class);
    }
}
