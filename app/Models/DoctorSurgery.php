<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorSurgery extends Model
{
    /*------------------------------------
    | Model Configuration
    |-----------------------------------*/

    protected $table = 'doctor_surgery';

    protected $fillable = [
        'doctor_id',
        'surgery_id',
        'doctor_role_id',
        'invoice_id',
        'amount',
    ];

    protected $casts = [
        'amount' => 'integer',
        'created_at' => 'datetime:Y-m-d H:i',
        'updated_at' => 'datetime:Y-m-d H:i',
    ];

    /*------------------------------------
    | Relationships
    |-----------------------------------*/

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class)->withDefault([
            'name' => 'Deleted Doctor',
        ]);
    }

    public function surgery(): BelongsTo
    {
        return $this->belongsTo(Surgery::class)->withDefault([
            'patient_name' => 'Deleted Surgery',
        ]);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(DoctorRole::class, 'doctor_role_id')->withDefault([
            'title' => 'Unknown Role',
        ]);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class)->withDefault();
    }

    public function doctorRole(): BelongsTo
    {
        return $this->belongsTo(DoctorRole::class, 'doctor_role_id');
    }

    /*------------------------------------
    | Business Logic Methods
    |-----------------------------------*/

    public function calculateAmount(): int
    {
        $baseAmount = $this->surgery->price ?? 0;
        $percentage = $this->role->percentage ?? 0;

        return (int) ($baseAmount * ($percentage / 100));
    }

    /*------------------------------------
    | Static Calculation Methods
    |-----------------------------------*/

    public static function totalForSurgery(int $surgeryId): int
    {
        return self::query()->where('surgery_id', $surgeryId)->sum('amount');
    }

    public static function totalForDoctor(int $doctorId): int
    {
        return self::query()->where('doctor_id', $doctorId)->sum('amount');
    }
}
