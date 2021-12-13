<?php

namespace App\Models;

use App\Enums\MonoEventType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use BenSampo\Enum\Traits\CastsEnums;

class WebhookUpdates extends Model
{
    use HasFactory;
    use CastsEnums;

    protected $fillable = [
        'user_id',
        'event_type',
        'dump',
    ];

    protected $cast = [
        'updated_at' => 'datetime',
        'created_at' => 'datetime',
        'dump' => 'array'
    ];

    protected $enumCast = [
        "event_type" => MonoEventType::class
    ];
}
