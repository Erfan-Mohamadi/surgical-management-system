<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class DoctorRole extends Model
{
    use HasFactory;

    /*------------------------------------
    | Model Configuration
    |-----------------------------------*/

    protected $fillable = [
        'title',
        'required',
        'quota',
        'status',
    ];

    protected $casts = [
        'required' => 'boolean',
        'status' => 'boolean',
    ];

    protected $table = 'doctor_roles';

    /*------------------------------------
    | Relationships
    |-----------------------------------*/

    public function doctors()
    {
        return $this->belongsToMany(
            Doctor::class,
            'doctor_doctor_role',
            'role_id',  // (DoctorRole)
            'doctor_id' // (Doctor)
        )->withTimestamps();
    }

    public function surgeries()
    {
        return $this->belongsToMany(Surgery::class, 'doctor_surgery')
            ->using(DoctorSurgery::class)
            ->withPivot(['doctor_role_id', 'amount', 'invoice_id', 'created_at', 'updated_at']);
    }

    /*------------------------------------
    | Business Logic Methods
    |-----------------------------------*/

    public static function getSum(): int
    {
        return (int) DoctorRole::query()->sum('quota');
    }

    /*------------------------------------
    | Caching Methods
    |-----------------------------------*/

    public static function getWithActiveDoctors()
    {
        return Cache::rememberForever('doctor_roles_with_active_doctors', function () {
            return self::with(['doctors' => function ($query) {
                $query->where('status', 1)->select('doctors.id', 'doctors.name');
            }])->get();
        });
    }

    public static function clearDoctorRolesCache(): void
    {
        Cache::forget('doctor_roles_with_active_doctors');
    }

    /*------------------------------------
    | Model Events
    |-----------------------------------*/

    protected static function booted()
    {
        // Clear cache on save (create/update)
        static::saved(fn () => self::clearDoctorRolesCache());

        // Clear cache on delete
        static::deleted(fn () => self::clearDoctorRolesCache());
    }
}
