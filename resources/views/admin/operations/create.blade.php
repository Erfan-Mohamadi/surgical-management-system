@extends('layouts.master')

@section('title', 'ثبت عملیات جدید')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.operations.index') }}">لیست عملیات‌ها</a></li>
    <li class="breadcrumb-item active">ثبت عملیات جدید</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">فرم ثبت عملیات</h3>
                </div>
                <form action="{{ route('admin.operations.store') }}" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">نام عملیات</label>
                            <input type="text" class="form-control" id="name" name="name" required autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="price">قیمت (تومان)</label>
                            <input type="number" class="form-control" id="price" name="price" min="0" required autocomplete="off">
                        </div>
                        <div class="form-check">
                            <input type="hidden" name="status" value="0">
                            <input type="checkbox" class="form-check-input" id="status" name="status" value="1" checked>
                            <label class="form-check-label" for="status">فعال</label>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">ثبت</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
