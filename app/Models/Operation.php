<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Operation extends Model
{
    use HasFactory;

    /*------------------------------------
    | Model Configuration
    |-----------------------------------*/

    protected $fillable = [
        'name',
        'price',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'price' => 'integer',
    ];

    /*------------------------------------
    | Relationships
    |-----------------------------------*/

    public function surgeries()
    {
        return $this->belongsToMany(
            Surgery::class,
            'operation_surgery',
            'operation_id',
            'surgery_id'
        )->withTimestamps();
    }

    /*------------------------------------
    | Business Logic Methods
    |-----------------------------------*/

    public function isDeletable(): bool
    {
        return $this->surgeries()->doesntExist();
    }

    public function deleteWithRelations(): void
    {
        $this->delete();
    }

    /*------------------------------------
    | Caching Methods
    |-----------------------------------*/

    public static function getActive()
    {
        return Cache::rememberForever('operations_active', function () {
            return self::where('status', true)->get();
        });
    }

    public static function clearOperationsCache(): void
    {
        Cache::forget('operations_active');
    }

    /*------------------------------------
    | Model Events
    |-----------------------------------*/

    protected static function booted(): void
    {
        // Clear cache on save (create/update)
        static::saved(fn () => self::clearOperationsCache());

        // Clear cache on delete
        static::deleted(fn () => self::clearOperationsCache());

        // Prevent deletion if used in surgeries
        static::deleting(function (Operation $operation) {
            abort_unless(
                $operation->isDeletable(),
                403,
                'امکان حذف پزشک وجود ندارد زیرا دارای عمل‌های جراحی با پرداخت ثبت شده است'
            );
        });
    }
}
