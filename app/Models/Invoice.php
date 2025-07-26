<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    /*------------------------------------
    | Model Configuration
    |-----------------------------------*/

    protected $fillable = [
        'doctor_id',
        'amount',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /*------------------------------------
    | Relationships
    |-----------------------------------*/

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function doctorSurgeries()
    {
        return $this->hasMany(DoctorSurgery::class, 'invoice_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function surgeries()
    {
        return $this->hasManyThrough(
            Surgery::class,
            DoctorSurgery::class,
            'invoice_id',
            'id',
            'id',
            'surgery_id'
        );
    }

    /*------------------------------------
    | Business Logic Methods
    |-----------------------------------*/
    // (Add any business logic methods here when needed)
}
