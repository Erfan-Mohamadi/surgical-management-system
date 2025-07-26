@extends('layouts.master')

@section('title', 'ثبت پزشک جدید')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.doctors.index') }}">لیست پزشکان</a></li>
    <li class="breadcrumb-item active">ثبت پزشک جدید</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">ثبت پزشک جدید</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.doctors.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">نام کامل</label>
                                    <input type="text" name="name" id="name" class="form-control" required autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="speciality_id">تخصص</label>
                                    <select name="speciality_id" id="speciality_id" class="form-control" required autocomplete="off">
                                        @foreach($specialities as $speciality)
                                            <option value="{{ $speciality->id }}">{{ $speciality->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="national_code">کد ملی</label>
                                    <input type="text" name="national_code" id="national_code" class="form-control" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="medical_number">شماره نظام پزشکی</label>
                                    <input type="text" name="medical_number" id="medical_number" class="form-control" autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">شماره تماس</label>
                                    <input type="text" name="phone" id="phone" class="form-control" required autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">رمز عبور</label>
                                    <input type="password" name="password" id="password" class="form-control" required autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <div class="form-check mt-3">
                            <input type="hidden" name="status" value="0">
                            <input type="checkbox"
                                   name="status"
                                   id="status"
                                   value="1"
                                   class="form-check-input"
                                @checked(old('status', $doctor->status ?? true))>
                            <label for="status" class="form-check-label">فعال</label>
                        </div>
                        <div class="form-group mt-3">
                            <label for="roles">نقش‌های پزشک</label>
                            <select name="roles[]" id="roles" class="form-control select2" multiple>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">ثبت پزشک</button>
                            <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">انصراف</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
