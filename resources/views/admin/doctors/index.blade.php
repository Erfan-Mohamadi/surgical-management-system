@extends('layouts.master')

@section('title', 'لیست پزشکان')

@section('breadcrumb')
    <li class="breadcrumb-item active">لیست پزشکان</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">لیست پزشکان</h3>
                    <a href="{{ route('admin.doctors.create') }}" class="btn btn-primary float-end">ثبت پزشک جدید</a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>نام پزشک</th>
                            <th>تخصص</th>
                            <th>شماره تماس</th>
                            <th>کد ملی</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($doctors as $doctor)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $doctor->name }}</td>
                                <td>{{ $doctor->speciality->title }}</td>
                                <td>{{ $doctor->phone }}</td>
                                <td>{{ $doctor->national_code ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $doctor->status ? 'success' : 'danger' }}">
                                        {{ $doctor->status ? 'فعال' : 'غیرفعال' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="btn btn-warning btn-sm">ویرایش</a>
                                    <a class="btn btn-primary btn-sm" href="{{ route('admin.doctors.show', $doctor->id) }}" role="button">
                                        نمایش
                                    </a>
                                    @if ($doctor->isDeletable())
                                        <button class="btn btn-danger btn-sm" onclick="confirmDelete('delete-{{ $doctor->id }}')">
                                            حذف
                                        </button>
                                        <form action="{{ route('admin.doctors.destroy', $doctor->id) }}" method="POST" id="delete-{{ $doctor->id }}">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @else
                                        <button class="btn btn-secondary btn-sm" disabled>حذف غیرمجاز</button>
                                    @endif
                                    <form action="{{ route('admin.doctors.destroy', $doctor->id) }}" method="post"
                                          id="delete-{{ $doctor->id }}">
                                        @csrf
                                        @method('delete')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $doctors->links() }}
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
