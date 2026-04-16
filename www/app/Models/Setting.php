<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Helper paramétrico hiper-rápido para acessar keys cacheados 
     * Se houver modificação de admin, o Cache clear é rodado!
     */
    public static function get(string $key, $default = null)
    {
        return Cache::rememberForever("settings.{$key}", function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    public static function set(string $key, $value)
    {
        $setting = self::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::put("settings.{$key}", $value);
        return $setting;
    }

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($setting) {
            Cache::forget("settings.{$setting->key}");
        });

        static::deleted(function ($setting) {
            Cache::forget("settings.{$setting->key}");
        });
    }
}
