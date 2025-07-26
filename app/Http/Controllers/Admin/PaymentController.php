<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Peyment\PeymentStoreRequest;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /*------------------------------------
    | PAYMENT OPERATIONS
    |-----------------------------------*/

    /**
     * Store a new payment record
     */
    public function store(PeymentStoreRequest $request)
    {
        $invoice = Invoice::with('payments')->findOrFail($request->invoice_id);
        $validated = $request->validated();

        // Prepare payment data
        $data = $request->only(['invoice_id', 'amount', 'pay_type', 'due_date', 'description']);
        $data['status'] = $request->pay_type === 'cash' ? 1 : 0;

        // Handle receipt file upload if exists
        if ($request->hasFile('receipt')) {
            $data['receipt'] = $request->file('receipt')->store('receipts', 'public');
        }

        $payment = Payment::create($data);

        // Log payment creation (Persian)
        Helper::addToLog('پرداخت', $payment, 'پرداخت با موفقیت ثبت شد.', [
            'شماره فاکتور' => $payment->invoice_id,
            'مبلغ پرداخت‌' => $payment->formatted_amount,
            'روش پرداخت' => Payment::getPayTypeLabel($payment->pay_type),
            'وضعیت پرداخت' => $payment->status_label,
        ]);

        return back()->with('success', 'پرداخت با موفقیت ثبت شد.');
    }

    /**
     * Delete a payment record
     */
    public function destroy(Payment $payment)
    {
        DB::transaction(function () use ($payment) {
            // Delete receipt file if exists
            if ($payment->receipt) {
                \Storage::disk('public')->delete($payment->receipt);
            }

            // Log payment deletion (Persian)
            Helper::addToLog('پرداخت', $payment, 'پرداخت حذف شد.', [
                'شماره فاکتور' => $payment->invoice_id,
                'مبلغ پرداخت‌' => $payment->formatted_amount,
                'روش پرداخت' => Payment::getPayTypeLabel($payment->pay_type),
                'وضعیت پرداخت' => Payment::getStatusLabel($payment->status),
            ]);

            $payment->delete();
        });

        return back()->with('success', 'پرداخت حذف شد و وضعیت فاکتور بروزرسانی شد.');
    }

    /**
     * Toggle payment status
     */
    public function updateStatus(Payment $payment)
    {
        $payment->status = ! $payment->status;
        $payment->save();

        // Log status update (Persian)
        Helper::addToLog('پرداخت', $payment, 'وضعیت پرداخت بروزرسانی شد.', [
            'شماره فاکتور' => $payment->invoice_id,
            'مبلغ پرداخت‌' => $payment->formatted_amount,
            'روش پرداخت' => Payment::getPayTypeLabel($payment->pay_type),
            'وضعیت جدید' => $payment->status_label,
        ]);

        return redirect()->back()->with('success', 'وضعیت پرداخت و فاکتور بروزرسانی شد.');
    }
}
