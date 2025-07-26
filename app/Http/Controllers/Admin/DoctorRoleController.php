<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\DoctorRole;
use Illuminate\Http\Request;

class DoctorRoleController extends Controller
{
    /*------------------------------------
    | CRUD OPERATIONS
    |-----------------------------------*/

    /**
     * Display paginated list of doctor roles
     */
    public function index()
    {
        $roles = DoctorRole::query()->oldest('id')->paginate(10);

        return view('admin.doctor_roles.index', compact('roles'));
    }

    /**
     * Show role editing form
     */
    public function edit(DoctorRole $doctorRole)
    {
        return view('admin.doctor_roles.edit', compact('doctorRole'));
    }

    /*------------------------------------
    | UPDATE OPERATION
    |-----------------------------------*/

    /**
     * Update doctor role details
     */
    public function update(Request $request, DoctorRole $doctorRole)
    {
        // Calculate available quota (100% - other roles' quotas)
        $otherRolesQuota = DoctorRole::query()->where('id', '!=', $doctorRole->id)->sum('quota');
        $availableQuota = 100 - $otherRolesQuota;

        // Validate request with custom quota validation
        $request->validate([
            'title' => 'required|string|max:191|unique:doctor_roles,title,'.$doctorRole->id,
            'quota' => [
                'required',
                'integer',
                'min:0',
                function ($attribute, $value, $fail) use ($availableQuota) {
                    if ($value > $availableQuota) {
                        $fail("مجموع سهمیه‌ها نمی‌تواند از ۱۰۰% بیشتر شود. حداکثر سهمیه قابل اختصاص: {$availableQuota}%");
                    }
                },
            ],
            'required' => 'required|in:0,1',
            'status' => 'required|in:0,1',
        ]);

        // Update role with validated data
        $doctorRole->update($request->all());

        // Log the update action (Persian)
        Helper::addToLog('نقش دکتر', $doctorRole, 'نقش‌ها ویرایش شد',
            [
                'نقش' => $doctorRole->title,
                'سهم' => $doctorRole->quota,
            ]
        );

        return redirect()->route('admin.doctor_roles.index')
            ->with('success', 'نقش با موفقیت ویرایش شد');
    }
}
