@extends('layouts.master')

@section('title', 'گزارش پزشک')

@section('content')
    <div class="container mt-4" id="report-section">
        <div class="card">
            <div class="card-header justify-content-between align-items-center">
                <h4>
                    گزارش جراحی‌های دکتر {{ $doctor->name }} -
                    @if($payment_status === '1')
                        پرداخت شده
                    @elseif($payment_status === '0')
                        در انتظار پرداخت
                    @else
                        همه پرداخت‌ها
                    @endif
                    <button onclick="window.print()" class="btn btn-outline-primary btn-sm float-end">
                        <i class="bi bi-printer"></i> چاپ
                    </button>
                </h4>
            </div>


            <div class="card-body">
                @if($doctorSurgeries->isEmpty())
                    <p class="text-muted">هیچ جراحی‌ای برای این پزشک یافت نشد.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped text-center">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>بیمار</th>
                                <th>شماره پرونده</th>
                                <th>تاریخ جراحی</th>
                                <th>نقش</th>
                                <th>مبلغ (تومان)</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($doctorSurgeries as $index => $s)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $s->surgery->patient_name }}</td>
                                    <td>{{ $s->surgery->document_number }}</td>
                                    <td>{{ $s->surgery->surgeried_at ? verta($s->surgery->surgeried_at)->format('Y-m-d') : '-' }}</td>
                                    <td>{{ $s->role->title }}</td>
                                    <td>{{ number_format($s->filtered_amount) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr class="fw-bold">
                                <td colspan="5">جمع کل</td>
                                <td>{{ number_format($totalAmount) }}</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #report-section, #report-section * {
                visibility: visible;
            }
            #report-section {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            button, .btn {
                display: none !important;
            }
        }
    </style>
@endpush
