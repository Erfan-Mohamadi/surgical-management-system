<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorSurgery;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /*------------------------------------
    | INVOICE LISTING
    |-----------------------------------*/

    /**
     * Display paginated list of invoices with filters
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $doctorName = $request->input('doctor_name');
        $status = $request->input('status');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Build filtered query
        $invoices = Invoice::with('doctor')
            ->when($doctorName, function (Builder $query) use ($doctorName) {
                return $query->whereHas('doctor', function ($q) use ($doctorName) {
                    $q->where('name', 'like', '%' . $doctorName . '%');
                });
            })
            ->when($status, function (Builder $query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($startDate, function (Builder $query) use ($startDate) {
                return $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function (Builder $query) use ($endDate) {
                return $query->whereDate('created_at', '<=', $endDate);
            })
            ->latest('created_at')
            ->paginate(10);

        return view('admin.invoices.index', compact('invoices'));
    }

    /*------------------------------------
    | INVOICE CREATION
    |-----------------------------------*/

    /**
     * Show invoice creation form
     */
    public function create(Request $request)
    {
        $doctors = Doctor::query()->select('name', 'id')->get();
        $doctorSurgeries = collect();

        // Pre-filter doctor surgeries if parameters exist
        if ($request->filled('doctor_id') && $request->filled('start_date') && $request->filled('end_date')) {
            $doctorId = $request->input('doctor_id')[0];
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            $doctorSurgeries = DoctorSurgery::with(['doctor', 'surgery'])
                ->where('doctor_id', $doctorId)
                ->whereNull('invoice_id')
                ->whereHas('surgery', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('surgeried_at', [$startDate, $endDate]);
                })
                ->get();
        }

        return view('admin.invoices.create', compact('doctors', 'doctorSurgeries'));
    }

    /**
     * Store new invoice from selected surgeries
     */
    public function store(Request $request)
    {
        $request->validate([
            'selected_ids' => 'bail|required|array|min:1',
        ], [
            'selected_ids.required' => 'حداقل یک جراحی را انتخاب کنید.',
        ]);

        $doctorId = $request->input('doctor_id')[0];
        $selectedIds = $request->input('selected_ids');

        // Validate selected surgeries
        $doctorSurgeries = DoctorSurgery::whereIn('id', $selectedIds)->get();

        if ($doctorSurgeries->isEmpty()) {
            return back()->with('error', 'هیچ جراحی انتخاب نشده است.');
        }

        if ($doctorSurgeries->pluck('doctor_id')->unique()->count() > 1) {
            return back()->with('error', 'همه جراحی‌ها باید مربوط به یک پزشک باشند.');
        }

        // Create invoice
        $totalAmount = $doctorSurgeries->sum('amount');
        $doctorName = Doctor::where('id', $doctorId)->value('name');

        $invoice = Invoice::create([
            'doctor_id' => $doctorId,
            'amount' => $totalAmount,
            'status' => false,
            'description' => 'فاکتور برای جراحی‌های انتخاب شده',
        ]);

        // Link surgeries to invoice
        DoctorSurgery::whereIn('id', $selectedIds)->update(['invoice_id' => $invoice->id]);

        // Log creation (Persian)
        Helper::addToLog('صورت حساب',$invoice,'صورت حساب ایجاد شد',
            [
                'نام دکتر' => $doctorName,
                'مبلغ کل' => $totalAmount,
                'توضیحات' => 'صورت حساب برای جراحی های انتخاب شده ایجاد شد',
            ]
        );

        return redirect()->route('admin.invoices.show', $invoice->id)
            ->with('success', 'صورت حساب ایجاد شد. حالا می‌توانید پرداخت اضافه کنید.');
    }

    /*------------------------------------
    | BULK OPERATIONS
    |-----------------------------------*/

    /**
     * Create invoice from bulk selected surgeries
     */
    public function bulkEdit(Request $request)
    {
        $selectedIds = $request->input('selected_ids', []);
        $description = trim($request->input('description'));

        // Validate selection
        if (empty($selectedIds)) {
            return back()->with('error', 'هیچ جراحی انتخاب نشده است.');
        }

        if (empty($description)) {
            $description = 'فاکتور ایجاد شده برای چند جراحی';
        }

        // Get doctor info from first selected surgery
        $firstSurgery = DoctorSurgery::findOrFail($selectedIds[0]);
        $doctorId = $firstSurgery->doctor_id;
        $doctorName = $firstSurgery->doctor->name;

        // Create invoice
        $totalAmount = DoctorSurgery::whereIn('id', $selectedIds)->sum('amount');

        $invoice = Invoice::create([
            'doctor_id' => $doctorId,
            'amount' => $totalAmount,
            'status' => false,
            'description' => $description,
        ]);

        // Link surgeries to invoice
        DoctorSurgery::whereIn('id', $selectedIds)->update(['invoice_id' => $invoice->id]);

        // Log bulk creation (Persian)
        Helper::addToLog('فاکتور',$invoice,'فاکتور ایجاد شد.',
            [
                'نام دکتر' => $doctorName,
                'مبلغ کل' => $totalAmount,
                'توضیحات' => $description,
            ]
        );

        return redirect()->route('admin.invoices.show', $invoice->id)
            ->with('success', 'فاکتور با موفقیت ایجاد شد.');
    }

    /*------------------------------------
    | INVOICE DETAILS
    |-----------------------------------*/

    /**
     * Show invoice details with payments
     */
    public function show($id)
    {
        $invoice = Invoice::with(['doctor', 'doctorSurgeries.surgery', 'payments'])->findOrFail($id);
        return view('admin.invoices.show', compact('invoice'));
    }

    /*------------------------------------
    | INVOICE DELETION
    |-----------------------------------*/

    /**
     * Delete invoice and related payments
     */
    public function destroy(Invoice $invoice)
    {
        DB::transaction(function () use ($invoice) {
            $doctorName = $invoice->doctor->name ?? '---';

            // Delete all payments and receipts
            foreach ($invoice->payments as $payment) {
                if ($payment->receipt) {
                    \Storage::disk('public')->delete($payment->receipt);
                }
                $payment->delete();
            }

            // Unlink doctor surgeries
            $invoice->doctorSurgeries()->update(['invoice_id' => null]);

            $amount = $invoice->amount;
            $desc = $invoice->description;
            $invoice->delete();

            // Log deletion (Persian)
            Helper::addToLog('فاکتور',$invoice,'فاکتور و پرداخت‌های مرتبط حذف شدند',
                [
                    'نام دکتر' => $doctorName,
                    'مبلغ کل' => $amount,
                    'توضیحات' => $desc,
                ]
            );
        });

        return back()->with('success', 'فاکتور و پرداخت‌های مرتبط با موفقیت حذف شدند و جراحی‌ها آزاد شدند.');
    }
}
