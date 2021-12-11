<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\traits\SimpleTokenGeneratorTrait;
use App\Notifications\EmailTokenVerification;
use App\Notifications\TestNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Notification;

class EmailVerification extends Model
{
    use HasFactory;
    use Notifiable;
    use SimpleTokenGeneratorTrait;
    const EXPIRATION_TIME = 15;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'token',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'id',
        'created_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function getUnverifiedEmail($data)
    {
        return EmailVerification::where('token', $data['token'])
            ->where('email', $data['email'])
            ->whereNull('verified_at')->first();
    }

    public function isExpired(): bool
    {
        return $this->isVerified() && $this->updated_at->addMinutes(self::EXPIRATION_TIME) <= Carbon::now();
    }

    public function isVerified(): bool
    {
        return !is_null($this->verified_at);
    }

    public function resendVerificationToken()
    {
        if ($this->isExpired()) {
            $this->token = $this->generateToken();
            $this->email_verified_at = null;
            $this->save();

            $this->notify(new EmailTokenVerification());
        };

        return $this;
    }

    public function newVerificationEmail($email)
    {
        $emailVerification = EmailVerification::create([
            'email' => $email,
            'token' => $this->generateToken()
        ]);

        $emailVerification->notify(new EmailTokenVerification());

        return $emailVerification;
    }
}
