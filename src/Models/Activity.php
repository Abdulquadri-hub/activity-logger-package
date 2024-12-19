<?php

namespace AbdulQuadri\ActivityLogger\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'model_type',
        'model_id',
        'payload',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'payload' => 'array'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('activity-logger.user_model', 'App\Models\User'));
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public static function log(string $type, $model = null, ?array $payload = null): self
    {
        return static::create([
            'user_id' => auth()->id(),
            'type' => $type,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'payload' => $payload,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent()
        ]);
    }
}





