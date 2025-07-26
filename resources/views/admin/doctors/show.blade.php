@extends('layouts.master')

@section('title', 'مشخصات پزشک')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.doctors.index') }}">لیست پزشکان</a></li>
    <li class="breadcrumb-item active">مشخصات پزشک</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">مشخصات پزشک</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>نام کامل:</span>
                                    <span>{{ $doctor->name }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>تخصص:</span>
                                    <span>{{ $doctor->speciality->title }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>کد ملی:</span>
                                    <span>{{ $doctor->national_code ?? '-' }}</span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>شماره نظام پزشکی:</span>
                                    <span>{{ $doctor->medical_number ?? '-' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>شماره تماس:</span>
                                    <span>{{ $doctor->phone }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>وضعیت:</span>
                                    <span class="badge bg-{{ $doctor->status ? 'success' : 'danger' }}">
                                        {{ $doctor->status ? 'فعال' : 'غیرفعال' }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="btn btn-warning">ویرایش</a>
                    <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">بازگشت به لیست</a>
                </div>
            </div>
        </div>
    </div>
@endsection
