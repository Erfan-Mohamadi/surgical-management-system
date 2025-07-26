@extends('layouts.master')

@section('title', 'لیست صورت حساب‌ها')

@section('breadcrumb')
    <li class="breadcrumb-item active">لیست صورت حساب‌ها</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">لیست صورت حساب‌ها</h3>
                    <a href="{{ route('admin.invoices.create') }}" class="btn btn-primary float-end">
                        <i class="fas fa-plus"></i> ثبت صورت حساب‌ جدید
                    </a>
                </div>
                <div class="card-body">

                    <form action="{{ route('admin.invoices.index') }}" method="GET" class="mb-3">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="end_date">نام پزشک :</label>
                                <input type="text" name="doctor_name" class="form-control"
                                       placeholder="نام پزشک" value="{{ request('doctor_name') }}" autocomplete="off">
                            </div>

                            <div class="col-md-2">
                                <label for="end_date">وضعیت پرداخت :</label>
                                <select name="status" class="form-control">
                                    <option value="">-- همه وضعیت‌ها --</option>
                                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>تسویه شده</option>
                                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>باز</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="start_date">از تاریخ :</label>
                                <input type="text" class="form-control" name="start_date" id="start_date"
                                       value="{{ request('start_date') }}"  autocomplete="off" >
                            </div>

                            <div class="col-md-2">
                                <label for="end_date">تا تاریخ :</label>
                                <input type="text" class="form-control" name="end_date" id="end_date"
                                       value="{{ request('end_date') }}"  autocomplete="off" >
                            </div>

                            <div class="col-md-3">
                                <label for="submit" style="visibility: hidden;">دکمه‌ها</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary flex-grow-1">
                                        <i class="fas fa-search"></i> جست‌وجو
                                    </button>
                                    @if(request()->hasAny(['doctor_name', 'status', 'start_date', 'end_date']))
                                        <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> پاک‌سازی
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>

                    <table class="table table-bordered table-hover">
                        <thead class="bg-light">
                        <tr>
                            <th>#</th>
                            <th>نام پزشک</th>
                            <th>مبلغ باقی مانده</th>
                            <th>توضیحات</th>
                            <th>تاریخ ثبت</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($invoices as $invoice)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $invoice->doctor->name }}</td>
                                <td>{{ number_format(max($invoice->amount - $invoice->payments->where('status', 1)->sum('amount'), 0)) }} تومان</td>
                                <td>{{ $invoice->description }}</td>
                                <td>{{ verta($invoice->created_at)->format('Y/m/d') ?? '-' }}</td>
                                <td>
                                    @if($invoice->status == 1)
                                        <span class="badge text-bg-success">تسویه شده</span>
                                    @else
                                        <span class="badge text-bg-danger">باز</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        @if($invoice->status == 0)
                                            <a href="{{ route('admin.invoices.show', $invoice) }}"
                                               class="btn btn-sm btn-primary" title="نمایش"
                                               style=" margin-left: 1rem; border-radius: 5px">
                                                <i class="fas fa-eye"></i>
                                                ویرایش
                                            </a>
                                        @else
                                            <a href="{{ route('admin.invoices.show', $invoice) }}"
                                               class="btn btn-sm btn-primary" title="نمایش"
                                               style=" margin-left: 1rem; border-radius: 5px">
                                                <i class="fas fa-eye"></i>
                                                نمایش
                                            </a>
                                        @endif
                                            <button class="btn btn-sm btn-danger" style=" margin-left: 1rem; border-radius: 5px"
                                                    onclick="confirmDelete('delete-{{ $invoice->id }}')" title="حذف">
                                                <i class="fas fa-trash"></i>
                                                حذف
                                            </button>

                                        <form action="{{ route('admin.invoices.destroy', $invoice) }}"
                                              method="POST" id="delete-{{ $invoice->id }}">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">هیچ صورت حسابی یافت نشد</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                @if($invoices->hasPages())
                    <div class="card-footer">
                        {{ $invoices->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmDelete(formId) {
            Swal.fire({
                title: 'آیا مطمئن هستید؟',
                text: "این عمل قابل بازگشت نیست!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'بله، حذف شود',
                cancelButtonText: 'انصراف'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            })
        }
    </script>
@endpush
@section('scripts')
    <script>
        $('#start_date').persianDatepicker({ showGregorianDate: true });
        $('#end_date').persianDatepicker({ showGregorianDate: true });

        $('#operation_id').select2({
            placeholder: "انتخاب عمل‌ها"
        });
    </script>
@endsection
