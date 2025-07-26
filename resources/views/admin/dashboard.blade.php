@php
    use App\Models\DoctorRole;
@endphp

@extends('layouts.master')

@section('title', 'داشبورد')

@section('content')
    @if(session()->has('status'))
        <div class="alert alert-success" role="alert">
            <strong>{{ session('status') }}</strong>
        </div>
    @endif
    @if(session('doctor_role_warning'))
        <div class="alert alert-danger text-center">
            {{ session('doctor_role_warning') }}
            <a href="{{ route('admin.doctor_roles.index') }}" class="ms-2">مشاهده و اصلاح</a>
        </div>
    @endif
    @if($upcomingCheques->count() > 0)
        <div class="alert alert-warning">
            <h5>یادآوری چک‌ها (۷ روز آینده) :</h5>
            <ul>
                @foreach($upcomingCheques as $cheque)
                    <li>
                        دکتر: {{ $cheque->invoice->doctor->name ?? '---' }} ,
                        فاکتور شماره: {{ $cheque->invoice_id }} ,
                        مبلغ: {{ number_format($cheque->amount) }} تومان ,
                        تاریخ سررسید: {{ verta($cheque->due_date)->format('Y/m/d') }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif




@endsection

