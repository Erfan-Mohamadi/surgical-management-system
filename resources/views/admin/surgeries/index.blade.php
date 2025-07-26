@extends('layouts.master')

@section('title', 'لیست جراحی‌ها')

@section('breadcrumb')
    <li class="breadcrumb-item active">لیست جراحی‌ها</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">لیست جراحی‌ها</h3>
                    <a href="{{ route('admin.surgeries.create') }}" class="btn btn-primary float-end">ثبت جراحی جدید</a>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.surgeries.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="patient_name">نام بیمار</label>
                                    <input type="text" name="patient_name" id="patient_name" class="form-control"
                                           value="{{ request('patient_name') }}" placeholder="نام بیمار" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="patient_national_code">کد ملی</label>
                                    <input type="text" name="patient_national_code" id="patient_national_code"
                                           class="form-control" value="{{ request('patient_national_code') }}"
                                           placeholder="کد ملی بیمار" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="document_number">شماره پرونده</label>
                                    <input type="text" name="document_number" id="document_number"
                                           class="form-control" value="{{ request('document_number') }}"
                                           placeholder="شماره پرونده" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-3">
                                    <label for="surgeried_at">تاریخ جراحی</label>
                                    <input type="text" name="surgeried_at" id="surgeried_at"
                                           class="form-control" value="{{ request('surgeried_at') }}" autocomplete="off">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">جستجو</button>
                                    @if(request()->hasAny(['patient_name', 'patient_national_code', 'document_number', 'surgeried_at']))
                                        <a href="{{ route('admin.surgeries.index') }}" class="btn btn-secondary">
                                            پاکسازی
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>

                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>نام بیمار</th>
                            <th>کد ملی</th>
                            <th>بیمه پایه</th>
                            <th>بیمه تکمیلی</th>
                            <th>شماره پرونده</th>
                            <th>تاریخ جراحی</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($surgeries as $surgery)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $surgery->patient_name }}</td>
                                <td>{{ $surgery->patient_national_code }}</td>
                                <td>
                                    @if ($surgery->basicInsurance)
                                        {{ $surgery->basicInsurance->name }}
                                    @else
                                        ندارد
                                    @endif
                                </td>
                                <td>
                                    @if ($surgery->supplementaryInsurance)
                                        {{ $surgery->supplementaryInsurance->name }}
                                    @else
                                        ندارد
                                    @endif
                                </td>
                                <td>{{ $surgery->document_number }}</td>
                                <td>{{ verta($surgery->surgeried_at)->format('Y/m/d') }}</td>
                                <td>
                                    <a href="{{ route('admin.surgeries.edit', $surgery->id) }}" class="btn btn-warning btn-sm">ویرایش</a>
                                    <a href="{{ route('admin.surgeries.show', $surgery->id) }}" class="btn btn-primary btn-sm">نمایش</a>

                                    @if ($surgery->isDeletable())
                                        <button class="btn btn-danger btn-sm" onclick="confirmDelete('delete-{{ $surgery->id }}')">
                                            حذف
                                        </button>
                                        <form action="{{ route('admin.surgeries.destroy', $surgery->id) }}" method="POST" id="delete-{{ $surgery->id }}">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @else
                                        <button class="btn btn-secondary btn-sm" disabled>حذف غیرمجاز</button>
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $surgeries->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmDelete(formId) {
            if (confirm('آیا مطمئن هستید؟ با حذف عمل صورت حساب ها و پرداخت ها پاک میشوند!')) {
                document.getElementById(formId).submit();
            }
        }

    </script>
@endpush
@section('scripts')
    <script>
        $('#surgeried_at').persianDatepicker({ showGregorianDate: true });

    </script>
@endsection
