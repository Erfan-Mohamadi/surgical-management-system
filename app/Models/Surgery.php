<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Surgery extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_name',
        'patient_national_code',
        'basic_insurance_id',
        'supp_insurance_id',
        'document_number',
        'speciality_id',
        'description',
        'surgeried_at',
        'released_at',
    ];

    protected $dates = [
        'surgeried_at',
        'released_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'surgeried_at' => 'datetime',
        'released_at' => 'datetime',
    ];

    // ------------------------
    // Relations
    // ------------------------

    public function basicInsurance()
    {
        return $this->belongsTo(Insurance::class, 'basic_insurance_id');
    }

    public function supplementaryInsurance()
    {
        return $this->belongsTo(Insurance::class, 'supp_insurance_id');
    }

    public function operations()
    {
        return $this->belongsToMany(Operation::class)
            ->withPivot('amount')
            ->withTimestamps();
    }

    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'doctor_surgery')
            ->withPivot('doctor_role_id', 'amount', 'invoice_id')
            ->withTimestamps();
    }

    public function doctorSurgeries()
    {
        return $this->hasMany(DoctorSurgery::class);
    }

    // ------------------------
    // Cache Handling
    // ------------------------

    public function clearAllCaches(): void
    {
        Cache::forget('surgeries');
        Cache::forget('basic_insurances');
        Cache::forget('supplementary_insurances');
        Cache::forget('operations_active');
        Cache::forget('doctor_roles_with_active_doctors');
    }

    // ------------------------
    // Helpers
    // ------------------------

    public function isDeletable(): bool
    {
        return $this->doctorSurgeries()
            ->whereHas('invoice.payments')
            ->doesntExist();
    }

    public static function booted()
    {
        static::deleting(function (Surgery $surgery) {
            abort_unless($surgery->isDeletable(), 403, 'این جراحی قابل حذف نیست زیرا پرداخت یا فاکتور دارد.');
        });

        static::saved(function (Surgery $surgery) {
            $surgery->clearAllCaches();
        });

        static::deleted(function (Surgery $surgery) {
            $surgery->clearAllCaches();
        });
    }

    public function calculateDoctorAmount(int $percent): int
    {
        $totalOperationCost = $this->operations()->sum('price');

        return (int) round($totalOperationCost * ($percent / 100));
    }

    public function attachOperations(array $operationIds): void
    {
        foreach ($operationIds as $operationId) {
            $operation = Operation::findOrFail($operationId);
            $this->operations()->attach($operationId, ['amount' => $operation->price]);
        }
    }

    public function syncOperations(array $operationIds): void
    {
        $operations = Operation::whereIn('id', $operationIds)->get();
        $syncData = [];
        foreach ($operations as $operation) {
            $syncData[$operation->id] = ['amount' => $operation->price];
        }
        $this->operations()->sync($syncData);
    }

    public function assignDoctors(array $doctorRolesInput, array $doctorRoles): void
    {
        foreach ($doctorRolesInput as $roleId => $doctorId) {
            if (empty($doctorId) || ! isset($doctorRoles[$roleId])) {
                continue;
            }
            $amount = $this->calculateDoctorAmount($doctorRoles[$roleId]);
            $this->doctorSurgeries()->create([
                'doctor_id' => $doctorId,
                'doctor_role_id' => $roleId,
                'amount' => $amount,
            ]);
        }
    }

    public function deleteWithRelations(): void
    {
        DB::transaction(function () {
            $doctorSurgeries = $this->doctorSurgeries()->with('invoice.payments')->get();

            foreach ($doctorSurgeries as $doctorSurgery) {
                if ($doctorSurgery->invoice) {
                    foreach ($doctorSurgery->invoice->payments as $payment) {
                        if ($payment->receipt) {
                            Storage::disk('public')->delete($payment->receipt);
                        }
                        $payment->delete();
                    }
                    $doctorSurgery->invoice->delete();
                }
                $doctorSurgery->delete();
            }

            $this->operations()->detach();
            $this->delete();
        });
    }
}
