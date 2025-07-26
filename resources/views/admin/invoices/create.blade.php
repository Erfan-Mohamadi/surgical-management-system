@extends('layouts.master')

@section('title', 'ثبت صورت حساب')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.invoices.create') }}">لیست صورت حساب‌ها</a></li>
    <li class="breadcrumb-item active">ثبت صورت حساب</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">ثبت صورت حساب جدید</h3>
                </div>
                <div class="card-body">

                    <form action="{{ route('admin.invoices.create') }}" method="GET">
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label>پزشک</label>
                                <select class="form-control" name="doctor_id[]" required>
                                    <option value="">--- انتخاب دکتر ---</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" {{ request('doctor_id.0') == $doctor->id ? 'selected' : '' }}>
                                            {{ $doctor->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>از تاریخ</label>
                                <input type="text" name="start_date" id="start_date" class="form-control"
                                       value="{{ request('start_date') }}" required autocomplete="off">
                            </div>
                            <div class="col-md-3">
                                <label>تا تاریخ</label>
                                <input type="text" name="end_date" id="end_date" class="form-control"
                                       value="{{ request('end_date') }}" required autocomplete="off">
                            </div>
                            <div class="col-md-3 align-self-end">
                                <button type="submit" class="btn btn-primary w-100">جست‌وجو</button>
                            </div>
                        </div>
                    </form>

                    @if(session('error'))
                        <div class="alert alert-danger mt-3">{{ session('error') }}</div>
                    @endif

                    @if($doctorSurgeries->isNotEmpty())
                        <hr>
                        <h5 class="mt-4">جراحی‌های یافت‌شده</h5>

                        <form action="{{ route('admin.invoices.bulk-edit') }}" method="POST" id="invoice-form">
                            @csrf
                            <table class="table table-bordered mt-2">
                                <thead>
                                <tr>
                                    <th>انتخاب</th>
                                    <th>نام پزشک</th>
                                    <th>نام بیمار</th>
                                    <th>تاریخ عمل</th>
                                    <th>مبلغ</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($doctorSurgeries as $ds)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="selected_ids[]" value="{{ $ds->id }}"
                                                   class="surgery-checkbox" data-amount="{{ $ds->amount }}">
                                        </td>
                                        <td>{{ $ds->doctor->name }}</td>
                                        <td>{{ $ds->surgery->patient_name }}</td>
                                        <td>{{ $ds->surgery->surgeried_at }}</td>
                                        <td>{{ number_format($ds->amount) }} تومان</td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>مجموع انتخاب شده:</strong></td>
                                    <td><span id="total-selected">0</span> تومان</td>
                                </tr>
                                <tr>
                                    <td colspan="5">
                                        <label for="description">توضیحات:</label>
                                        <textarea name="description" id="description" class="form-control" rows="2"
                                                  placeholder="توضیحات مورد نظر خود را وارد کنید..."></textarea>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                            <button type="submit" class="btn btn-warning mt-3">ثبت صورت حساب</button>
                        </form>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $("#start_date").persianDatepicker({ showGregorianDate: true });
        $("#end_date").persianDatepicker({ showGregorianDate: true });

        function updateTotal() {
            let total = 0;
            $(".surgery-checkbox:checked").each(function () {
                total += parseInt($(this).data("amount"));
            });
            $("#total-selected").text(total.toLocaleString('fa-IR'));
        }

        $(document).on("change", ".surgery-checkbox", updateTotal);
    </script>
@endsection
