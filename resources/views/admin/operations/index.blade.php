@extends('layouts.master')

@section('title', 'لیست عملیات‌ها')

@section('breadcrumb')
    <li class="breadcrumb-item active">لیست عملیات‌ها</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">لیست عملیات‌ها</h3>
                    <a href="{{ route('admin.operations.create') }}" class="btn btn-primary float-end">ثبت عملیات جدید</a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>نام عملیات</th>
                            <th>قیمت (تومان)</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($operations as $operation)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $operation->name }}</td>
                                <td>{{ number_format($operation->price) }}</td>
                                <td>
                                    @if($operation->status)
                                        <span class="badge text-bg-success">فعال</span>
                                    @else
                                        <span class="badge text-bg-danger">غیرفعال</span>
                                    @endif
                                </td>

                                <td>
                                    <a href="{{ route('admin.operations.edit', $operation->id) }}" class="btn btn-warning btn-sm">ویرایش</a>
                                    @if ($operation->isDeletable())
                                        <button class="btn btn-danger btn-sm" onclick="confirmDelete('delete-{{ $operation->id }}')">
                                            حذف
                                        </button>
                                        <form action="{{ route('admin.operations.destroy', $operation->id) }}" method="POST" id="delete-{{ $operation->id }}">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @else
                                        <button class="btn btn-secondary btn-sm" disabled>حذف غیرمجاز</button>
                                    @endif
                                    <form action="{{ route('admin.operations.destroy', $operation->id) }}" method="post"
                                          id="delete-{{ $operation->id }}">
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
                    {{ $operations->links() }}
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
