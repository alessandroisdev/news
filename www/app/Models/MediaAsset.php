<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaAsset extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'file_name',
        'file_path',
        'mime_type',
        'size',
        'disk',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
