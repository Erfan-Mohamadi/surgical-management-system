@extends('layouts.master')

@section('title', 'گزارش پرداخت‌ها پزشک')

@section('breadcrumb')
    <li class="breadcrumb-item active">گزارش پرداخت‌ها پزشک</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">گزارش جراحی‌های پزشک</h3>
                </div>
                <div class="card-body">

                    <form action="{{ route('admin.reports.doctor.show') }}" method="GET" target="_blank">
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label for="doctor_id">پزشک</label>
                                <select name="doctor_id" id="doctor_id" class="form-control" required>
                                    <option value="">--- انتخاب دکتر ---</option>
                                    @foreach ($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                            {{ $doctor->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="from_date">از تاریخ</label>
                                <input type="text" name="from_date" id="from_date" class="form-control" value="{{ request('from_date') }}" required autocomplete="off">
                            </div>

                            <div class="col-md-3">
                                <label for="to_date">تا تاریخ</label>
                                <input type="text" name="to_date" id="to_date" class="form-control" value="{{ request('to_date') }}" autocomplete="off">
                            </div>

                            <div class="col-md-3">
                                <label for="payment_status">وضعیت پرداخت</label>
                                <select name="payment_status" id="payment_status" class="form-control">
                                    <option value="all" {{ request('payment_status') == 'all' ? 'selected' : '' }}>همه</option>
                                    <option value="1" {{ request('payment_status') == '1' ? 'selected' : '' }}>پرداخت شده</option>
                                    <option value="0" {{ request('payment_status') == '0' ? 'selected' : '' }}>در انتظار پرداخت</option>
                                </select>

                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-search"></i> جستجو
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $("#from_date").persianDatepicker({ showGregorianDate: true });
        $("#to_date").persianDatepicker({ showGregorianDate: true });
    </script>
@endsection
