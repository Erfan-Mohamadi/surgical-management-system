@extends('layouts.master')

@section('title', 'مشخصات بیمه')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.insurances.index') }}">لیست بیمه‌ها</a></li>
    <li class="breadcrumb-item active">مشخصات بیمه</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">مشخصات بیمه</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>نام بیمه:</span>
                                    <span>{{ $insurance->name }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>نوع بیمه:</span>
                                    <span>{{ $insurance->type == 'basic' ? 'پایه' : 'تکمیلی' }}</span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>درصد تخفیف:</span>
                                    <span>{{ $insurance->discount }}%</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>وضعیت:</span>
                                    <span class="badge bg-{{ $insurance->status ? 'success' : 'danger' }}">
                                        {{ $insurance->status ? 'فعال' : 'غیرفعال' }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.insurances.edit', $insurance->id) }}" class="btn btn-warning">ویرایش</a>
                    <a href="{{ route('admin.insurances.index') }}" class="btn btn-secondary">بازگشت به لیست</a>
                </div>
            </div>
        </div>
    </div>
@endsection
