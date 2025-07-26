@extends('layouts.master')

@section('title', 'لیست نقش‌های پزشکی')

@section('breadcrumb')
    <li class="breadcrumb-item active">لیست نقش‌های پزشکی</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">لیست نقش‌های پزشکی</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>عنوان نقش</th>
                            <th>ضروری</th>
                            <th>سهمیه (درصد)</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($roles as $role)
                            <tr class="align-middle">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $role->title }}</td>
                                <td>
                                    @if($role->required)
                                        <span class="badge text-bg-success">بله</span>
                                    @else
                                        <span class="badge text-bg-secondary">خیر</span>
                                    @endif
                                </td>
                                <td>{{ $role->quota }}%</td>
                                <td>
                                    @if($role->status)
                                        <span class="badge text-bg-success">فعال</span>
                                    @else
                                        <span class="badge text-bg-danger">غیرفعال</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a class="btn btn-warning btn-sm" style=" margin-left: 1rem; border-radius: 5px" href="{{ route('admin.doctor_roles.edit', $role->id) }}" role="button">
                                            ویرایش
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $roles->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
