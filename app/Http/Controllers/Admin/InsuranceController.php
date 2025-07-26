<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Insurance\InsuranceStoreRequest;
use App\Http\Requests\Admin\Insurance\InsuranceUpdateRequest;
use App\Models\Insurance;

class InsuranceController extends Controller
{
    /*------------------------------------
    | HELPER METHODS
    |-----------------------------------*/

    /**
     * Get Persian label for insurance type
     */
    private function getTypeLabel($type)
    {
        return $type === 'basic' ? 'پایه' : 'تکمیلی';
    }

    /*------------------------------------
    | CRUD OPERATIONS
    |-----------------------------------*/

    /**
     * Display paginated list of insurances
     */
    public function index()
    {
        $insurances = Insurance::query()->latest('id')->paginate(10);

        return view('admin.insurances.index', compact('insurances'));
    }

    /**
     * Show insurance creation form
     */
    public function create()
    {
        return view('admin.insurances.create');
    }

    /**
     * Store new insurance record
     */
    public function store(InsuranceStoreRequest $request)
    {
        $validated = $request->validated();
        $insurance = Insurance::create($validated);

        // Log creation (Persian)
        Helper::addToLog('بیمه', $insurance, 'بیمه جدید ثبت شد',
            [
                'نام' => $insurance->name,
                'نوع' => $this->getTypeLabel($insurance->type),
                'تخفیف' => $insurance->discount,
            ]
        );

        return redirect()->route('admin.insurances.index')
            ->with('success', 'بیمه جدید با موفقیت ثبت شد');
    }

    /**
     * Show insurance details
     */
    public function show(Insurance $insurance)
    {
        return view('admin.insurances.show', compact('insurance'));
    }

    /**
     * Show insurance edit form
     */
    public function edit(Insurance $insurance)
    {
        return view('admin.insurances.edit', compact('insurance'));
    }

    /**
     * Update insurance record
     */
    public function update(InsuranceUpdateRequest $request, Insurance $insurance)
    {
        $validated = $request->validated();
        $insurance->update($validated);

        // Log update (Persian)
        Helper::addToLog('بیمه', $insurance, 'بیمه ویرایش شد',
            [
                'نام' => $insurance->name,
                'نوع' => $this->getTypeLabel($insurance->type),
                'تخفیف' => $insurance->discount,
            ]
        );

        return redirect()->route('admin.insurances.index')
            ->with('success', 'اطلاعات بیمه با موفقیت ویرایش شد');
    }

    /**
     * Delete insurance record
     */
    public function destroy(Insurance $insurance)
    {
        if (! $insurance->isDeletable()) {
            return redirect()->route('admin.insurances.index')
                ->with('error', 'این بیمه در جراحی‌ها استفاده شده و قابل حذف نیست');
        }

        // Log deletion (Persian)
        Helper::addToLog('بیمه', $insurance, 'بیمه حذف شد', [
            'نام' => $insurance->name,
            'نوع' => $this->getTypeLabel($insurance->type),
        ]);

        $insurance->delete();

        return redirect()->route('admin.insurances.index')
            ->with('success', 'بیمه با موفقیت حذف شد');
    }
}
