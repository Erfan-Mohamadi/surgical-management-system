@extends('layouts.master')

@section('title', 'ثبت جراحی جدید')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.surgeries.index') }}">لیست جراحی‌ها</a></li>
    <li class="breadcrumb-item active">ثبت جراحی جدید</li>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">

                <div class="card-header">
                    <h3 class="card-title">ثبت جراحی جدید</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.surgeries.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="patient_name">نام بیمار</label>
                                    <input type="text" name="patient_name" id="patient_name" class="form-control" value="{{ old('patient_name') }}" required autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="patient_national_code">کد ملی بیمار</label>
                                    <input type="text" name="patient_national_code" id="patient_national_code" class="form-control" value="{{ old('patient_national_code') }}" required autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="basic_insurance_id">بیمه پایه</label>
                                    <select name="basic_insurance_id" id="basic_insurance_id" class="form-control">
                                        <option value="" @selected(old('basic_insurance_id') === null || old('basic_insurance_id') === '')>ندارد</option>
                                        @foreach($basicInsurances as $insurance)
                                            <option value="{{ $insurance->id }}" @selected(old('basic_insurance_id') == $insurance->id)>
                                                {{ $insurance->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="supp_insurance_id">بیمه تکمیلی</label>
                                    <select name="supp_insurance_id" id="supp_insurance_id" class="form-control">
                                        <option value="" @selected(old('supp_insurance_id') === null || old('supp_insurance_id') === '')>ندارد</option>
                                        @foreach($suppInsurances as $insurance)
                                            <option value="{{ $insurance->id }}" @selected(old('supp_insurance_id') == $insurance->id)>
                                                {{ $insurance->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="document_number">شماره پرونده</label>
                                    <input type="text" name="document_number" id="document_number" class="form-control" value="{{ old('document_number') }}" required autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="surgeried_at">تاریخ عمل</label>
                                    <input type="text" name="surgeried_at" id="surgeried_at" class="form-control" value="{{ old('surgeried_at') }}" required autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="released_at">تاریخ ترخیص</label>
                                    <input type="text" name="released_at" id="released_at" class="form-control" value="{{ old('released_at') }}" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <label for="description">توضیحات</label>
                            <textarea name="description" id="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="operation_id">عمل‌ها</label>
                            <select name="operation_id[]" id="operation_id" class="form-control select2" multiple required>
                                @foreach($operations as $operation)
                                    <option value="{{ $operation->id }}">{{ $operation->name }} ({{ number_format($operation->price) }} تومان)</option>
                                @endforeach
                            </select>
                        </div>

                        @push('scripts')
                            <script>
                                $(document).ready(function () {
                                    $('#operation_id').select2({
                                        placeholder: "انتخاب عمل‌ها"
                                    });
                                });
                            </script>
                        @endpush
                        <div class="row mt-3">
                            <label class="mb-1 mt-1">پزشک ها</label>
                            @foreach($doctorRoles as $doctorRole)
                                <div class="col">
                                    <div class="form-group">
                                        <label class="form-label" for="">{{ $doctorRole->title }}</label>
                                        <select class="form-control" name="doctor_roles[{{ $doctorRole->id }}]"
                                                id="doctor_roles_{{ $doctorRole->id }}" {{ $doctorRole->required ? 'required' : '' }}>
                                            <option value="" class="text-muted">--- انتخاب دکتر --</option>
                                            @foreach($doctorRole->doctors as $doctor)
                                                <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">ثبت جراحی</button>
                            <a href="{{ route('admin.surgeries.index') }}" class="btn btn-secondary">انصراف</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $("#surgeried_at").persianDatepicker({
            showGregorianDate: true,
        });
    </script>

    <script>
        $("#released_at").persianDatepicker({
            showGregorianDate: true,
        });
    </script>
@endsection
