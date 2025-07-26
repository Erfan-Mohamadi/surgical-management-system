@extends('layouts.master')

@section('title', 'لیست تخصص‌ها')

@section('breadcrumb')
    <li class="breadcrumb-item active">لیست تخصص‌ها</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">لیست تخصص‌ها</h3>
                    <a href="{{ route('admin.specialities.create') }}" class="btn btn-primary float-end">ثبت تخصص جدید</a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>عنوان تخصص</th>
                            <th>وضعیت</th>
                            <th>تاریخ ایجاد</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($specialities as $speciality)
                            <tr class="align-middle">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $speciality->title }}</td>
                                <td>
                                    @if($speciality->status)
                                        <span class="badge text-bg-success">فعال</span>
                                    @else
                                        <span class="badge text-bg-danger">غیرفعال</span>
                                    @endif
                                </td>
                                <td>{{ verta($speciality->created_at)->format('Y/m/d H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a class="btn btn-warning btn-sm m" style=" margin-left: 1rem; border-radius: 5px" href="{{ route('admin.specialities.edit', $speciality->id) }}" role="button">
                                            ویرایش
                                        </a>
                                        <button class="btn btn-danger btn-sm" style=" margin-left: 1rem; border-radius: 5px" type="button" onclick="confirmDelete('delete-{{ $speciality->id }}')" @disabled(!$speciality->isDeletable())>
                                            حذف
                                        </button>
                                        <form action="{{ route('admin.specialities.destroy', $speciality->id) }}" method="post" id="delete-{{ $speciality->id }}">
                                            @csrf
                                            @method('delete')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $specialities->links() }}
                </div>
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
