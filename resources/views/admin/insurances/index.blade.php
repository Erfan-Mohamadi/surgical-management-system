@extends('layouts.master')

@section('title', 'لیست بیمه‌ها')

@section('breadcrumb')
    <li class="breadcrumb-item active">لیست بیمه‌ها</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">لیست بیمه‌ها</h3>
                    <a href="{{ route('admin.insurances.create') }}" class="btn btn-primary float-end">
                        <i class="fas fa-plus"></i> ثبت بیمه جدید
                    </a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-light">
                        <tr>
                            <th>#</th>
                            <th>نام بیمه</th>
                            <th>نوع</th>
                            <th>تخفیف (%)</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($insurances as $insurance)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $insurance->name }}</td>
                                <td>
                                    @if($insurance->type == 'basic')
                                        <span class="badge text-bg-success">پایه</span>
                                    @else
                                        <span class="badge text-bg-primary">تکمیلی</span>
                                    @endif
                                </td>
                                <td>{{ $insurance->discount }}</td>
                                <td>
                                    @if($insurance->status)
                                        <span class="badge text-bg-success">فعال</span>
                                    @else
                                        <span class="badge text-bg-danger">غیرفعال</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.insurances.edit', $insurance->id) }}"
                                           class="btn btn-sm btn-warning" title="ویرایش"
                                           style=" margin-left: 1rem; border-radius: 5px">
                                            <i class="fas fa-edit"></i>
                                            ویرایش
                                        </a>
                                        <a href="{{ route('admin.insurances.show', $insurance->id) }}"
                                           class="btn btn-sm btn-primary" title="نمایش"
                                           style=" margin-left: 1rem; border-radius: 5px">
                                            <i class="fas fa-eye"></i>
                                            نمایش
                                        </a>
                                        <button class="btn btn-sm btn-danger"
                                                style="margin-left: 1rem; border-radius: 5px"
                                                onclick="confirmDelete('delete-{{ $insurance->id }}')"
                                                title="حذف"
                                                @if(!$insurance->isDeletable()) disabled @endif>
                                            <i class="fas fa-trash"></i>
                                            حذف
                                        </button>

                                        <form action="{{ route('admin.insurances.destroy', $insurance->id) }}"
                                              method="POST" id="delete-{{ $insurance->id }}">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">هیچ بیمه‌ای ثبت نشده است</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                @if($insurances->hasPages())
                    <div class="card-footer">
                        {{ $insurances->links() }}
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
