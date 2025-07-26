@extends('layouts.master')

@section('title', 'ویرایش جراحی')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.surgeries.index') }}">لیست جراحی‌ها</a></li>
    <li class="breadcrumb-item active">ویرایش جراحی</li>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header"><h3 class="card-title">ویرایش جراحی</h3></div>
                <div class="card-body">
                    <form action="{{ route('admin.surgeries.update', $surgery->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-4">
                                <label for="patient_name">نام بیمار</label>
                                <input type="text" name="patient_name" class="form-control" required value="{{ old('patient_name', $surgery->patient_name) }}" autocomplete="off">
                            </div>
                            <div class="col-md-4">
                                <label for="patient_national_code">کد ملی بیمار</label>
                                <input type="text" name="patient_national_code" class="form-control" required value="{{ old('patient_national_code', $surgery->patient_national_code) }}" autocomplete="off">
                            </div>
                            <div class="col-md-2">
                                <label for="basic_insurance_id">بیمه پایه</label>
                                <select name="basic_insurance_id" class="form-control">
                                    <option value="">ندارد</option>
                                    @foreach($basicInsurances as $insurance)
                                        <option value="{{ $insurance->id }}" @selected(old('basic_insurance_id', $surgery->basic_insurance_id) == $insurance->id)>{{ $insurance->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="supp_insurance_id">بیمه تکمیلی</label>
                                <select name="supp_insurance_id" class="form-control">
                                    <option value="">ندارد</option>
                                    @foreach($suppInsurances as $insurance)
                                        <option value="{{ $insurance->id }}" @selected(old('supp_insurance_id', $surgery->supp_insurance_id) == $insurance->id)>{{ $insurance->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="document_number">شماره پرونده</label>
                                <input type="text" name="document_number" class="form-control" required value="{{ old('document_number', $surgery->document_number) }}" autocomplete="off">
                            </div>
                            <div class="col-md-3">
                                <label for="surgeried_at">تاریخ عمل</label>
                                <input type="text" name="surgeried_at" id="surgeried_at" class="form-control" value="{{ old('surgeried_at', $surgery->surgeried_at) }}" autocomplete="off">
                            </div>
                            <div class="col-md-3">
                                <label for="released_at">تاریخ ترخیص</label>
                                <input type="text" name="released_at" id="released_at" class="form-control" value="{{ old('released_at', $surgery->released_at) }}" autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <label for="description">توضیحات</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description', $surgery->description) }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="operation_id">عمل‌ها</label>
                            <select name="operation_id[]" id="operation_id" class="form-control select2" multiple required>
                                @foreach($operations as $operation)
                                    <option value="{{ $operation->id }}"
                                        @selected(in_array($operation->id, $surgery->operations->pluck('id')->toArray()))>
                                        {{ $operation->name }} ({{ number_format($operation->price) }} تومان)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row mt-3">
                            <label class="mb-1 mt-1">پزشک ها</label>
                            @foreach($doctorRoles as $doctorRole)
                                <div class="col">
                                    <label>{{ $doctorRole->title }}</label>
                                    <select name="doctor_roles[{{ $doctorRole->id }}]" class="form-control" {{ $doctorRole->required ? 'required' : '' }}>
                                        <option value="">--- انتخاب دکتر ---</option>
                                        @foreach($doctorRole->doctors as $doctor)
                                            <option value="{{ $doctor->id }}"
                                                @selected(old("doctor_roles.{$doctorRole->id}", $assignedDoctors[$doctorRole->id] ?? null) == $doctor->id)>
                                                {{ $doctor->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">ثبت تغییرات</button>
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
        $('#surgeried_at').persianDatepicker({ showGregorianDate: true });
        $('#released_at').persianDatepicker({ showGregorianDate: true });

        $('#operation_id').select2({
            placeholder: "انتخاب عمل‌ها"
        });
    </script>
@endsection
