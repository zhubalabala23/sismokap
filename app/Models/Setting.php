<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    
    protected $fillable = [
        'key',
        'value'
    ];

    /**
     * Get setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getValue(string $key, $default = null)
    {
        return \Illuminate\Support\Facades\Cache::remember("setting_{$key}", 86400, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set setting value by key.
     *
     * @param string $key
     * @param mixed $value
     * @return Setting
     */
    public static function setValue(string $key, $value)
    {
        \Illuminate\Support\Facades\Cache::forget("setting_{$key}");
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Get the logo URL.
     *
     * @return string|null
     */
    public static function getLogoUrl()
    {
        $logo = self::getValue('logo');
        if (!$logo) {
            return null;
        }

        if (filter_var($logo, FILTER_VALIDATE_URL)) {
            return $logo;
        }

        $disk = config('filesystems.default');
        if ($disk === 'supabase') {
            return \Illuminate\Support\Facades\Storage::disk('supabase')->url($logo);
        }

        return asset('storage/' . $logo);
    }
}
