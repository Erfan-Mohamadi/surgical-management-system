<?php

namespace App\Http\Requests\Admin\Peyment;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Invoice;

class PeymentStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $invoice = Invoice::with('payments')->find($this->invoice_id);
        $remaining = $invoice ? $invoice->amount - $invoice->payments->sum('amount') : 0;

        return [
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => "bail|required|numeric|min:1|max:$remaining",
            'pay_type' => 'required|in:cash,cheque',
            'due_date' => 'nullable|date',
            'receipt' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'description' => 'nullable|string',
        ];
    }

}
