<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Speciality extends Model
{
    use HasFactory;

    /*------------------------------------
    | Model Configuration
    |-----------------------------------*/

    protected $fillable = [
        'title',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /*------------------------------------
    | Relationships
    |-----------------------------------*/

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }

    /*------------------------------------
    | Business Logic Methods
    |-----------------------------------*/

    public function isDeletable(): bool
    {
        return $this->doctors()->doesntExist();
    }

    /*------------------------------------
    | Cache Management
    |-----------------------------------*/

    public function clearAllCaches(): void
    {
        Cache::forget('specialities');
    }

    /*------------------------------------
    | Model Events
    |-----------------------------------*/

    protected static function booted()
    {
        // Prevent deletion if speciality has doctors
        static::deleting(function (Speciality $speciality) {
            abort_unless(
                $speciality->isDeletable(),
                403,
                'cant delete'
            );
        });

        // Clear cache on save (create/update)
        static::saved(function (Speciality $speciality) {
            $speciality->clearAllCaches();
        });

        // Clear cache on delete
        static::deleted(function (Speciality $speciality) {
            $speciality->clearAllCaches();
        });
    }
}
