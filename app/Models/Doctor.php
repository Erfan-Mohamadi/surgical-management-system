<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Cache;

class Doctor extends Authenticatable
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'speciality_id',
        'national_code',
        'medical_number',
        'phone',
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'boolean',
    ];

    /* ======================= */
    /*      Model Events        */
    /* ======================= */

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::deleting(function (Doctor $doctor) {
            abort_unless($doctor->isDeletable(), 403, 'امکان حذف پزشک وجود ندارد زیرا دارای عمل‌های جراحی با پرداخت ثبت شده است');
        });

        static::saved(function (Doctor $doctor) {
            $doctor->clearAllCaches();
        });

        static::deleted(function (Doctor $doctor) {
            $doctor->clearAllCaches();
        });
    }

    /* ======================= */
    /*      Relationships      */
    /* ======================= */

    public function speciality()
    {
        return $this->belongsTo(Speciality::class);
    }

    public function doctorSurgeries()
    {
        return $this->hasMany(DoctorSurgery::class);
    }

    public function roles()
    {
        return $this->belongsToMany(
            DoctorRole::class,
            'doctor_doctor_role',
            'doctor_id',
            'role_id'
        )->withTimestamps();
    }

    public function surgeries()
    {
        return $this->belongsToMany(Surgery::class, 'doctor_surgery')
            ->withPivot('doctor_role_id', 'amount', 'invoice_id')
            ->withTimestamps();
    }

    /* ======================= */
    /*        Methods          */
    /* ======================= */

    /**
     * Clear all cached data related to doctors.
     */
    public function clearAllCaches(): void
    {
        Cache::forget('specialities');
        Cache::forget('$roles');
    }

    /* ======================= */
    /*      Helper Methods     */
    /* ======================= */

    public function isDeletable(): bool
    {
        return $this->doctorSurgeries()
            ->whereHas('invoice.payments')
            ->doesntExist();
    }

    public function deleteWithRelations(): void
    {
        $this->roles()->detach();
        $this->delete();
    }
}
