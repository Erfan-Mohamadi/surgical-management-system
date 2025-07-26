<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Operation\OperationStoreRequest;
use App\Http\Requests\Admin\Operation\OperationUpdateRequest;
use App\Models\Operation;
use Illuminate\Http\Request;

class OperationController extends Controller
{
    /*------------------------------------
    | CRUD OPERATIONS
    |-----------------------------------*/

    /**
     * Display paginated list of operations
     */
    public function index()
    {
        $operations = Operation::query()->latest('id')->paginate(10);
        return view('admin.operations.index', compact('operations'));
    }

    /**
     * Show operation creation form
     */
    public function create()
    {
        return view('admin.operations.create');
    }

    /**
     * Store new operation record
     */
    public function store(OperationStoreRequest $request)
    {
        $validated = $request->validated();
        $operation = Operation::query()->create($validated);
        $statusLabel = $operation->status ? 'فعال' : 'غیرفعال';

        // Log creation (Persian)
        Helper::addToLog('عمل',$operation,'عمل ثبت شد',
            [
                'نام' => $operation->name,
                'قیمت (تومان)' => $operation->price,
                'وضعیت' => $statusLabel,
            ]
        );

        return redirect()->route('admin.operations.index')
            ->with('success', 'عمل جدید با موفقیت ثبت شد');
    }

    /**
     * Show operation details
     */
    public function show(Operation $operation)
    {
        return view('admin.operations.show', compact('operation'));
    }

    /**
     * Show operation edit form
     */
    public function edit(Operation $operation)
    {
        return view('admin.operations.edit', compact('operation'));
    }

    /**
     * Update operation record
     */
    public function update(OperationUpdateRequest $request, Operation $operation)
    {
        $validated = $request->validated();
        $operation->update($validated);
        $statusLabel = $operation->status ? 'فعال' : 'غیرفعال';

        // Log update (Persian)
        Helper::addToLog('عمل',$operation,'عمل ویرایش شد',
            [
                'نام' => $operation->name,
                'قیمت (تومان)' => $operation->price,
                'وضعیت' => $statusLabel,
            ]
        );

        return redirect()->route('admin.operations.index')
            ->with('success', 'اطلاعات عمل با موفقیت ویرایش شد');
    }

    /**
     * Delete operation record
     */
    public function destroy(Operation $operation)
    {
        if (!$operation->isDeletable()) {
            return redirect()->route('admin.operations.index')
                ->with('error', 'این جراحی به دلیل داشتن پرداخت یا فاکتور قابل حذف نیست.');
        }

        $statusLabel = $operation->status ? 'فعال' : 'غیرفعال';
        $operation->deleteWithRelations();

        // Log deletion (Persian)
        Helper::addToLog('عمل',$operation,'عمل حذف شد',
            [
                'نام' => $operation->name,
                'قیمت (تومان)' => $operation->price,
                'وضعیت' => $statusLabel,
            ]
        );

        return redirect()->route('admin.operations.index')
            ->with('success', 'عمل با موفقیت حذف شد');
    }
}
