<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorSurgery;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class DoctorReportController extends Controller
{
    /*------------------------------------
    | REPORT DISPLAY METHODS
    |-----------------------------------*/

    /**
     * Show doctor selection form for reports
     */
    public function index()
    {
        // Get all doctors (id and name only)
        $doctors = Doctor::query()->select('id', 'name')->get();

        return view('admin.reports.doctor.index', compact('doctors'));
    }

    /*------------------------------------
    | REPORT GENERATION METHODS
    |-----------------------------------*/

    /**
     * Generate and display doctor-specific report
     */
    public function show(Request $request)
    {
        // Get selected doctor or fail
        $doctor = Doctor::query()->findOrFail($request->doctor_id);

        // Get payment status filter (default: 'all')
        $payment_status = $request->input('payment_status', 'all');

        // Query doctor surgeries with related data
        $doctorSurgeries = DoctorSurgery::with(['doctor', 'surgery', 'role', 'invoice.payments'])
            ->where('doctor_id', $doctor->id)
            // Date range filters
            ->when($request->filled('from_date'), function (Builder $query) use ($request) {
                $query->whereHas('surgery', function ($q) use ($request) {
                    $q->whereDate('surgeried_at', '>=', $request->from_date);
                });
            })
            ->when($request->filled('to_date'), function (Builder $query) use ($request) {
                $query->whereHas('surgery', function ($q) use ($request) {
                    $q->whereDate('surgeried_at', '<=', $request->to_date);
                });
            })
            // Only include invoices with payments
            ->whereHas('invoice.payments', function ($q) {
                $q->whereIn('status', [0, 1]);
            })
            ->get();

        // Calculate filtered amounts and total
        $totalAmount = 0;
        foreach ($doctorSurgeries as $ds) {
            $payments = $ds->invoice->payments ?? collect();

            // Apply payment status filter if not 'all'
            $payments = $payment_status !== 'all'
                ? $payments->where('status', (int)$payment_status)
                : $payments->whereIn('status', [0, 1]);

            $filtered_amount = $payments->sum('amount');
            $ds->filtered_amount = $filtered_amount;
            $totalAmount += $filtered_amount;
        }

        // Return report view with filtered data
        return view('admin.reports.doctor.show', [
            'doctor' => $doctor,
            'doctorSurgeries' => $doctorSurgeries->filter(fn($s) => $s->filtered_amount > 0),
            'totalAmount' => $totalAmount,
            'payment_status' => $payment_status
        ]);
    }
}
