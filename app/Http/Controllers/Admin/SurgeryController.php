<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Surgery\SurgeryStoreRequest;
use App\Http\Requests\Admin\Surgery\SurgeryUpdateRequest;
use App\Models\DoctorRole;
use App\Models\Insurance;
use App\Models\Operation;
use App\Models\Surgery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SurgeryController extends Controller
{
    /*------------------------------------
    | SURGERY LISTING
    |-----------------------------------*/

    /**
     * Display paginated list of surgeries with filters
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $patientName = $request->input('patient_name');
        $patientNationalCode = $request->input('patient_national_code');
        $documentNumber = $request->input('document_number');
        $surgeriedAt = $request->input('surgeried_at');

        // Build filtered query
        $surgeries = Surgery::with(['basicInsurance', 'supplementaryInsurance', 'operations'])
            ->when($patientName, function (Builder $query) use ($patientName) {
                return $query->where('patient_name', 'like', '%'.$patientName.'%');
            })
            ->when($patientNationalCode, function (Builder $query) use ($patientNationalCode) {
                return $query->where('patient_national_code', 'like', '%'.$patientNationalCode.'%');
            })
            ->when($documentNumber, function (Builder $query) use ($documentNumber) {
                return $query->where('document_number', 'like', '%'.$documentNumber.'%');
            })
            ->when($surgeriedAt, function (Builder $query) use ($surgeriedAt) {
                return $query->whereDate('surgeried_at', '=', $surgeriedAt);
            })
            ->latest('id')
            ->paginate(10);

        return view('admin.surgeries.index', compact('surgeries'));
    }

    /*------------------------------------
    | SURGERY CREATION
    |-----------------------------------*/

    /**
     * Show surgery creation form
     */
    public function create()
    {
        // Get cached data for form
        $basicInsurances = Insurance::getActiveBasic();
        $suppInsurances = Insurance::getActiveSupplementary();
        $operations = Operation::getActive();
        $doctorRoles = DoctorRole::getWithActiveDoctors();

        return view('admin.surgeries.create', compact(
            'basicInsurances',
            'suppInsurances',
            'operations',
            'doctorRoles'
        ));
    }

    /**
     * Store new surgery record
     */
    public function store(SurgeryStoreRequest $request)
    {
        $validated = $request->validated();
        $doctorRolesInput = $request->input('doctor_roles', []);

        // Create surgery and relationships
        $surgery = Surgery::query()->create($validated);
        $surgery->attachOperations($validated['operation_id']);
        $surgery->load('operations');

        // Assign doctors with prepared roles
        $doctorRoles = $this->prepareDoctorRoles($doctorRolesInput);
        $surgery->assignDoctors($doctorRolesInput, $doctorRoles);

        // Log creation (Persian)
        Helper::addToLog('جراحی', $surgery, 'جراحی ثبت شد',
            [
                'نام بیمار' => $surgery->patient_name,
                'شماره پرونده' => $surgery->document_number,
                'تاریخ عمل' => verta($surgery->surgeried_at)->format('Y/m/d'),
            ]
        );

        return redirect()->route('admin.surgeries.index')->with('success', 'جراحی با موفقیت ثبت شد.');
    }

    /*------------------------------------
    | SURGERY DISPLAY
    |-----------------------------------*/

    /**
     * Show surgery details
     */
    public function show(Surgery $surgery)
    {
        $surgery->load([
            'basicInsurance',
            'supplementaryInsurance',
            'operations',
            'doctorSurgeries.doctor',
            'doctorSurgeries.doctorRole',
        ]);

        return view('admin.surgeries.show', compact('surgery'));
    }

    /*------------------------------------
    | SURGERY EDITING
    |-----------------------------------*/

    /**
     * Show surgery edit form
     */
    public function edit(Surgery $surgery)
    {
        $surgery->load(['operations', 'doctors']);

        // Get cached data for form
        $basicInsurances = Insurance::getActiveBasic();
        $suppInsurances = Insurance::getActiveSupplementary();
        $operations = Operation::getActive();
        $doctorRoles = DoctorRole::getWithActiveDoctors();

        // Get assigned doctors mapped by role
        $assignedDoctors = $surgery->doctors()
            ->withPivot('doctor_role_id')
            ->get()
            ->mapWithKeys(fn ($doctor) => [$doctor->pivot->doctor_role_id => $doctor->id]);

        return view('admin.surgeries.edit', compact(
            'surgery',
            'basicInsurances',
            'suppInsurances',
            'operations',
            'doctorRoles',
            'assignedDoctors'
        ));
    }

    /**
     * Update surgery record
     */
    public function update(SurgeryUpdateRequest $request, Surgery $surgery)
    {
        $validated = $request->validated();
        $doctorRolesInput = $request->input('doctor_roles', []);

        // Update surgery and relationships
        $surgery->update($validated);
        $surgery->syncOperations($validated['operation_id']);
        $surgery->doctorSurgeries()->delete();
        $surgery->load('operations');

        // Reassign doctors with prepared roles
        $doctorRoles = $this->prepareDoctorRoles($doctorRolesInput);
        $surgery->assignDoctors($doctorRolesInput, $doctorRoles);

        // Log update (Persian)
        Helper::addToLog('جراحی', $surgery, 'جراحی ویرایش شد',
            [
                'نام بیمار' => $surgery->patient_name,
                'شماره پرونده' => $surgery->document_number,
                'تاریخ عمل' => verta($surgery->surgeried_at)->format('Y/m/d'),
            ]
        );

        return redirect()->route('admin.surgeries.index')->with('success', 'جراحی با موفقیت ویرایش شد.');
    }

    /*------------------------------------
    | SURGERY DELETION
    |-----------------------------------*/

    /**
     * Delete surgery record
     */
    public function destroy(Surgery $surgery)
    {
        if (! $surgery->isDeletable()) {
            return redirect()->route('admin.surgeries.index')
                ->with('error', 'این جراحی به دلیل داشتن پرداخت یا فاکتور قابل حذف نیست.');
        }

        $surgery->deleteWithRelations();

        // Log deletion (Persian)
        Helper::addToLog('جراحی', $surgery, 'جراحی  حذف شد',
            [
                'نام بیمار' => $surgery->patient_name,
                'شماره پرونده' => $surgery->document_number,
                'تاریخ عمل' => verta($surgery->surgeried_at)->format('Y/m/d'),
            ]
        );

        return redirect()->route('admin.surgeries.index')
            ->with('success', 'جراحی با موفقیت حذف شد.');
    }

    /*------------------------------------
    | HELPER METHODS
    |-----------------------------------*/

    /**
     * Prepare doctor roles with quota calculations
     */
    protected function prepareDoctorRoles(array $doctorRolesInput): array
    {
        $doctorRoles = DoctorRole::whereIn('id', array_keys($doctorRolesInput))
            ->pluck('quota', 'id')->toArray();

        // Handle special case for role ID 3 quota redistribution
        if (empty($doctorRolesInput[3]) && isset($doctorRolesInput[1]) && isset($doctorRoles[3])) {
            $doctorRoles[1] += $doctorRoles[3];
            unset($doctorRoles[3]);
        }

        return $doctorRoles;
    }
}
