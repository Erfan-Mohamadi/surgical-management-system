@extends('layouts.master')

@section('title', 'لیست پرداخت‌ها')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>لیست پرداخت‌ها</h3>
            <a href="{{ route('admin.payments.create') }}" class="btn btn-primary">افزودن پرداخت</a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>شناسه</th>
                    <th>فاکتور</th>
                    <th>مبلغ</th>
                    <th>روش پرداخت</th>
                    <th>وضعیت</th>
                    <th>عملیات</th>
                </tr>
                </thead>
                <tbody>
                @foreach($payments as $payment)
                    <tr>
                        <td>{{ $payment->id }}</td>
                        <td>#{{ $payment->invoice_id }}</td>
                        <td>{{ number_format($payment->amount) }} تومان</td>
                        <td>{{ $payment->pay_type == 'cash' ? 'نقدی' : 'چک' }}</td>
                        <td>{{ $payment->status_label }}</td>
                        <td>
                            <a href="{{ route('admin.payments.edit', $payment) }}" class="btn btn-sm btn-warning">ویرایش</a>
                            <form action="{{ route('admin.payments.destroy', $payment) }}" method="POST" class="d-inline" onsubmit="return confirm('حذف شود؟');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">حذف</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $payments->links() }}
        </div>
    </div>
@endsection
