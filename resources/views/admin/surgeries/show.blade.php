@extends('layouts.master')

@section('title', 'جزئیات جراحی')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.surgeries.index') }}">لیست جراحی‌ها</a></li>
    <li class="breadcrumb-item active">جزئیات جراحی</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="card-title">اطلاعات بیمار</h4>
                </div>
                <div class="card-body" >
                    <p><strong>نام بیمار:</strong> {{ $surgery->patient_name }}</p>
                    <p><strong>کد ملی:</strong> {{ $surgery->patient_national_code }}</p>
                    <p><strong>شماره پرونده:</strong> {{ $surgery->document_number }}</p>
                    <p><strong>تاریخ عمل:</strong> {{ verta($surgery->surgeried_at)->format('Y/m/d') }}</p>
                    <p><strong>تاریخ ترخیص:</strong> {{ verta($surgery->released_at)->format('Y/m/d') }}</p>
                    <p><strong>بیمه پایه:</strong> {{ $surgery->basicInsurance?->name ?? 'ندارد' }}</p>
                    <p><strong>بیمه تکمیلی:</strong> {{ $surgery->supplementaryInsurance?->name ?? 'ندارد' }}</p>
                    <p><strong>توضیحات:</strong> {{ $surgery->description ?? '---' }}</p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="card-title">عمل‌ها</h4>
                </div>
                <div class="card-body">
                        <ul class="list-group">
                            @foreach($surgery->operations as $operation)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $operation->name }}
                                    <span class="badge bg-primary">{{ number_format($operation->pivot->amount) }} تومان</span>
                                </li>
                            @endforeach
                        </ul>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="card-title">پزشکان</h4>
                </div>
                <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>نقش</th>
                                <th>نام پزشک</th>
                                <th>سهم (تومان)</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($surgery->doctorSurgeries as $ds)
                                <tr>
                                    <td>{{ $ds->doctorRole->title ?? '---' }}</td>
                                    <td>{{ $ds->doctor->name ?? '---' }}</td>
                                    <td>{{ number_format($ds->amount) }} تومان</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                </div>
            </div>

            <div class="mt-3">
                <a href="{{ route('admin.surgeries.index') }}" class="btn btn-secondary">بازگشت به لیست</a>
            </div>
        </div>
    </div>
@endsection
