<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Doctor\DoctorStoreRequest;
use App\Http\Requests\Admin\Doctor\DoctorUpdateRequest;
use App\Models\Doctor;
use App\Models\DoctorRole;
use App\Models\Speciality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DoctorController extends Controller
{
    // =============================================
    // CRUD OPERATIONS
    // =============================================

    /**
     * Display paginated list of doctors
     */
    public function index()
    {
        $doctors = Doctor::with('speciality:id,title')
            ->latest('id')
            ->select(['id','name','speciality_id','national_code','status','phone'])
            ->paginate(10);

        return view('admin.doctors.index', compact('doctors'));
    }

    /**
     * Show doctor creation form
     */
    public function create()
    {
        $specialities = Cache::rememberForever('specialities', function () {
            return Speciality::query()->where('status', true)->get(['id', 'title']);
        });
        $roles = Cache::rememberForever('$roles', function () {
            return DoctorRole::query()->where('status', true)->get();
        });

        return view('admin.doctors.create', compact('specialities', 'roles'));
    }

    /**
     * Store new doctor record
     */
    public function store(DoctorStoreRequest $request)
    {
        $validated = $request->validated();

        $doctor = Doctor::query()->create([
            'name' => $validated['name'],
            'speciality_id' => $validated['speciality_id'],
            'national_code' => $validated['national_code'],
            'medical_number' => $validated['medical_number'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'status' => $validated['status'],
        ]);

        if (!empty($validated['roles'])) {
            $doctor->roles()->sync($validated['roles']);
        }

        // Log creation
        Helper::addToLog('دکتر',$doctor,'پزشک جدید ثبت شد',
            ['نام' => $doctor->name]
        );

        return redirect()->route('admin.doctors.index')
            ->with('success', 'پزشک جدید با موفقیت ثبت شد');
    }

    /**
     * Display single doctor details
     */
    public function show(Doctor $doctor)
    {
        $doctor->load(['speciality', 'roles']);

        return view('admin.doctors.show', compact('doctor'));
    }

    /**
     * Show doctor edit form
     */
    public function edit(Doctor $doctor)
    {
        $specialities = Cache::rememberForever('specialities', function () {
            return Speciality::query()->where('status', true)->get(['id', 'title']);
        });
        $roles = Cache::rememberForever('$roles', function () {
            return DoctorRole::query()->where('status', true)->get();
        });
        $doctor->load('roles');

        return view('admin.doctors.edit', compact('doctor', 'specialities', 'roles'));
    }

    /**
     * Update doctor record
     */
    public function update(DoctorUpdateRequest $request, Doctor $doctor)
    {
        $validated = $request->validated();

        $updateData = [
            'name' => $validated['name'],
            'speciality_id' => $validated['speciality_id'],
            'national_code' => $validated['national_code'],
            'medical_number' => $validated['medical_number'],
            'phone' => $validated['phone'],
            'status' => $validated['status'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $doctor->update($updateData);
        $doctor->roles()->sync($validated['roles'] ?? []);

        // Log update
        Helper::addToLog('دکتر',$doctor,'طلاعات پزشک ویرایش شد',
            ['نام' => $doctor->name]
        );

        return redirect()->route('admin.doctors.index')
            ->with('success', 'اطلاعات پزشک با موفقیت ویرایش شد');
    }

    /**
     * Delete doctor record
     */
    public function destroy(Doctor $doctor)
    {
        if (!$doctor->isDeletable()) {
            return redirect()->route('admin.doctors.index')
                ->with('error', 'این جراحی به دلیل داشتن پرداخت یا فاکتور قابل حذف نیست.');
        }

        $doctor->deleteWithRelations();

        // Log deletion
        Helper::addToLog('دکتر',$doctor,'پزشک حذف شد',
            ['نام' => $doctor->name]
        );

        return redirect()->route('admin.doctors.index')
            ->with('success', 'پزشک با موفقیت حذف شد');
    }
}
