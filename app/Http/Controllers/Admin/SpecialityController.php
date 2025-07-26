<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Speciality\SpecialityStoreRequest;
use App\Http\Requests\Admin\Speciality\SpecialityUpdateRequest;
use App\Models\Speciality;
use Illuminate\Support\Facades\Cache;

class SpecialityController extends Controller
{
    /*------------------------------------
    | HELPER METHODS
    |-----------------------------------*/

    /**
     * Get Persian label for status
     */
    private function getStatusLabel($status)
    {
        return $status ? 'فعال' : 'غیرفعال';
    }

    /*------------------------------------
    | CRUD OPERATIONS
    |-----------------------------------*/

    /**
     * Display paginated list of specialities
     */
    public function index()
    {
        $specialities = Speciality::query()->latest('id')->paginate(10);
        return view('admin.specialities.index', compact('specialities'));
    }

    /**
     * Show speciality creation form
     */
    public function create()
    {
        return view('admin.specialities.create');
    }

    /**
     * Store new speciality record
     */
    public function store(SpecialityStoreRequest $request)
    {
        $validated = $request->validated();

        $speciality = Speciality::query()->create([
            'title' => $validated['title'],
            'status' => (bool) ($validated['status'] ?? false),
        ]);

        // Log creation (Persian)
        Helper::addToLog('تخصص‌ها',$speciality,'تخصص ثبت شد',
            [
                'عنوان تخصص' => $speciality->title,
                'وضعیت' => $this->getStatusLabel($speciality->status),
            ]
        );

        return redirect()->route('admin.specialities.index')
            ->with('success', 'تخصص با موفقیت ثبت شد.');
    }

    /**
     * Show speciality details
     */
    public function show(Speciality $speciality)
    {
        return view('admin.specialities.show', compact('speciality'));
    }

    /**
     * Show speciality edit form
     */
    public function edit(Speciality $speciality)
    {
        return view('admin.specialities.edit', compact('speciality'));
    }

    /**
     * Update speciality record
     */
    public function update(SpecialityUpdateRequest $request, Speciality $speciality)
    {
        $speciality->update([
            'title'  => $request->title,
            'status' => $request->status ?? $speciality->status,
        ]);

        // Log update (Persian)
        Helper::addToLog('تخصص‌ها', $speciality, 'تخصص به‌روزرسانی شد', [
            'عنوان تخصص' => $speciality->title,
            'وضعیت'      => $this->getStatusLabel($speciality->status),
        ]);

        return redirect()->route('admin.specialities.index')
            ->with('success', 'تخصص با موفقیت به‌روزرسانی شد');
    }

    /**
     * Delete speciality record
     */
    public function destroy(Speciality $speciality)
    {
        $speciality->delete();

        // Log deletion (Persian)
        Helper::addToLog('تخصص‌ها',$speciality,'تخصص حذف شد',
            [
                'عنوان تخصص' => $speciality->title,
                'وضعیت' => $this->getStatusLabel($speciality->status),
            ]
        );

        return redirect()->route('admin.specialities.index')
            ->with('success', 'تخصص با موفقیت حذف شد');
    }
}
