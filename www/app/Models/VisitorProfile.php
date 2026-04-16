<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorProfile extends Model
{
    protected $fillable = [
        'session_id', 'user_id', 'ip_address', 'device_type', 
        'browser', 'os', 'location_data', 'preferences_score', 
        'last_active_at'
    ];

    protected $casts = [
        'preferences_score' => 'array',
        'last_active_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
