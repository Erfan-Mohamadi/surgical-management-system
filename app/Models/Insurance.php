<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Insurance extends Model
{
    use HasFactory;

    /*------------------------------------
    | Model Configuration
    |-----------------------------------*/

    protected $fillable = [
        'name',
        'type',
        'discount',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'discount' => 'integer',
    ];

    /*------------------------------------
    | Relationships
    |-----------------------------------*/

    public function basicSurgeries()
    {
        return $this->hasMany(Surgery::class, 'basic_insurance_id');
    }

    public function supplementarySurgeries()
    {
        return $this->hasMany(Surgery::class, 'supp_insurance_id');
    }

    /*------------------------------------
    | Business Logic Methods
    |-----------------------------------*/

    public function isDeletable(): bool
    {
        return $this->basicSurgeries()->doesntExist()
            && $this->supplementarySurgeries()->doesntExist();
    }

    /*------------------------------------
    | Caching Methods
    |-----------------------------------*/

    public static function getActiveBasic()
    {
        return Cache::rememberForever('basic_insurances', function () {
            return self::where('type', 'basic')->where('status', 1)->get();
        });
    }

    public static function getActiveSupplementary()
    {
        return Cache::rememberForever('supplementary_insurances', function () {
            return self::where('type', 'supplementary')->where('status', 1)->get();
        });
    }

    public static function clearInsuranceCaches(): void
    {
        Cache::forget('basic_insurances');
        Cache::forget('supplementary_insurances');
    }

    /*------------------------------------
    | Model Events
    |-----------------------------------*/

    protected static function booted()
    {
        // Clear cache on save (create/update)
        static::saved(fn() => self::clearInsuranceCaches());

        // Clear cache on delete
        static::deleted(fn() => self::clearInsuranceCaches());

        // Prevent deletion if used in surgeries
        static::deleting(function (Insurance $insurance) {
            if (!$insurance->isDeletable()) {
                abort(403, 'این بیمه در جراحی‌ها استفاده شده و قابل حذف نیست');
            }
        });
    }
}
