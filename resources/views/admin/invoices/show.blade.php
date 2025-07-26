@extends('layouts.master')

@section('title', 'جزئیات فاکتور')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.invoices.index') }}">صورت حساب‌ها</a></li>
    <li class="breadcrumb-item active">
        @if(!$invoice->status)
            ویرایش فاکتور {{ $invoice->id }}
        @else
            نمایش فاکتور {{ $invoice->id }}
        @endif
    </li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">فاکتور شماره {{ $invoice->id }}</h4>
            <button onclick="window.print()" class="btn btn-primary float-end">
                <i class="bi bi-printer"></i>
            </button>
        </div>


        <div class="card-body">
            <p><strong>پزشک:</strong> {{ $invoice->doctor->name }}</p>
            <p><strong>مبلغ کل:</strong> {{ number_format($invoice->amount) }} تومان</p>
            <p>
                <strong>وضعیت:</strong>
                @if($invoice->status)
                    تسویه شده
                @else
                    باز
                @endif
            </p>
            <p><strong>مبلغ باقی‌مانده:</strong>
                {{ number_format(max($invoice->amount - $invoice->payments->where('status', 1)->sum('amount'), 0)) }} تومان
            </p>

            <hr>
            <h5>جراحی‌ها</h5>
            <table class="table">
                <thead>
                <tr>
                    <th>بیمار</th>
                    <th>تاریخ عمل</th>
                    <th>مبلغ</th>
                </tr>
                </thead>
                <tbody>
                @foreach($invoice->doctorSurgeries as $surgery)
                    <tr>
                        <td>{{ $surgery->surgery->patient_name }}</td>
                        <td>{{ verta($surgery->surgery->surgeried_at)->format('Y/m/d')}}</td>
                        <td>{{ number_format($surgery->amount) }} تومان</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <hr>
            <h5>پرداخت‌ها</h5>
            <table class="table">
                <thead>
                <tr>
                    <th>مبلغ</th>
                    <th>روش پرداخت</th>
                    <th>تاریخ سررسید</th>
                    <th>رسید</th>
                    <th>وضعیت پرداخت</th>
                    <th>توضیحات</th>
                    <th>عملیات</th>
                </tr>
                </thead>
                <tbody>
                @forelse($invoice->payments as $payment)
                    <tr>
                        <td>{{ number_format($payment->amount) }} تومان</td>
                        <td>{{ $payment->pay_type == 'cash' ? 'نقدی' : 'چک' }}</td>
                        <td>{{ $payment->due_date ? verta($payment->due_date)->format('Y/m/d') : '-' }}</td>
                        <td>
                            @if($payment->receipt)
                                <a href="{{ asset('storage/'.$payment->receipt) }}" target="_blank">مشاهده</a>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($payment->status == 0)
                                <p>در انتظار</p>
                            @else
                                <p>پرداخت شده</p>
                            @endif
                        </td>
                        <td>
                            {{ $payment->description }}
                        </td>
                        <td>
                                <div class="btn-group">
                                    <form id="delete-{{ $payment->invoice_id }}" action="{{ route('admin.payments.destroy', $payment->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm ml-3"
                                                style="margin-left: 1rem; border-radius: 5px"
                                                onclick="confirmDelete('delete-{{ $payment->invoice_id }}')" title="حذف">
                                            حذف
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.payments.updateStatus', $payment->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-warning btn-sm" style="margin-left: 1rem; border-radius: 5px">
                                            تغییر وضعیت
                                        </button>
                                    </form>
                                </div>

                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">هیچ پرداختی ثبت نشده است.</td></tr>
                @endforelse
                </tbody>
            </table>
            <div id="peyment-section">

            <hr>
            @if(!$invoice->status)
                <h5>افزودن پرداخت جدید</h5>
                <form action="{{ route('admin.payments.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                    <div class="row">
                        <div class="col-md-3">
                            @php
                                $remainingAmount = $invoice->amount - $invoice->payments->sum('amount');
                            @endphp
                            <input type="number" name="amount" class="form-control" placeholder="مبلغ" required autocomplete="off"
                                   max="{{ $remainingAmount }}" step="0.5">
                        </div>
                        <div class="col-md-3">
                            <select name="pay_type" class="form-control" required>
                                <option value="cash">نقدی</option>
                                <option value="cheque">چک</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input id="due_date" type="text" name="due_date" class="form-control" placeholder="تاریخ سررسید (برای چک)" autocomplete="off">
                        </div>
                        <div class="col-md-3">
                            <input type="file" name="receipt" class="form-control">
                        </div>
                    </div>
                    <div class="mt-3">
                        <textarea name="description" class="form-control" placeholder="توضیحات"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">ثبت پرداخت</button>
                </form>
                </div>
            @else
                <p class="text-success">این فاکتور به طور کامل تسویه شده است.</p>
            @endif

        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const amountInput = document.querySelector('input[name="amount"]');
            if (amountInput) {
                const originalMax = parseFloat(amountInput.max);

                function adjustMaxValue() {
                    const reducedMax = Math.floor(originalMax * 10) / 10;
                    amountInput.max = reducedMax;

                    if (parseFloat(amountInput.value) > reducedMax) {
                        amountInput.value = reducedMax;
                    }
                }
                adjustMaxValue();
            }
        });

        $("#due_date").persianDatepicker({ showGregorianDate: true });
    </script>
    <script>
        function confirmDelete(formId) {
            Swal.fire({
                title: 'آیا مطمئن هستید؟',
                text: "این عمل قابل بازگشت نیست!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'بله، حذف شود',
                cancelButtonText: 'انصراف'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            })
        }
    </script>

@endsection
