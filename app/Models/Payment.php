<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /*------------------------------------
    | Model Configuration
    |-----------------------------------*/

    protected $fillable = [
        'invoice_id',
        'amount',
        'pay_type',
        'due_date',
        'receipt',
        'description',
        'status',
    ];

    /*------------------------------------
    | Relationships
    |-----------------------------------*/

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /*------------------------------------
    | Scopes
    |-----------------------------------*/

    public function scopeUpcomingCheques($query)
    {
        return $query->where('pay_type', 'cheque')
            ->where('status', 0)
            ->whereDate('due_date', '>=', Carbon::now())
            ->whereDate('due_date', '<=', Carbon::now()->addDays(7));
    }

    /*------------------------------------
    | Accessors & Mutators
    |-----------------------------------*/

    public function getStatusLabelAttribute()
    {
        return $this->status ? 'پرداخت شده' : 'در انتظار پرداخت';
    }

    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount).' تومان';
    }

    /*------------------------------------
    | Static Helpers
    |-----------------------------------*/

    public static function getPayTypeLabel(string $type): string
    {
        return $type === 'cash' ? 'نقدی' : 'چک';
    }

    public static function getStatusLabel(bool $status): string
    {
        return $status ? 'پرداخت شده' : 'در انتظار پرداخت';
    }

    /*------------------------------------
    | Model Events
    |-----------------------------------*/

    protected static function booted()
    {
        // Prevent overpayment
        static::creating(function (Payment $payment) {
            $invoice = Invoice::with('payments')->find($payment->invoice_id);
            $totalPaid = $invoice->payments->where('status', 1)->sum('amount');

            if (($totalPaid + $payment->amount) > $invoice->amount) {
                abort(403, 'مبلغ پرداخت بیشتر از مانده فاکتور است.');
            }
        });

        // Update invoice status on payment change
        static::saved(function (Payment $payment) {
            $invoice = $payment->invoice;
            $totalPaid = $invoice->payments()->where('status', 1)->sum('amount');
            $invoice->update(['status' => $totalPaid >= $invoice->amount ? 1 : 0]);
        });

        // Update invoice status on payment deletion
        static::deleted(function (Payment $payment) {
            $invoice = $payment->invoice;
            $totalPaid = $invoice->payments()->where('status', 1)->sum('amount');
            $invoice->update(['status' => $totalPaid >= $invoice->amount ? 1 : 0]);
        });
    }
}
