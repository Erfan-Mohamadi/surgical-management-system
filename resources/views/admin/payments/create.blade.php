@extends('layouts.master')

@section('title', 'ثبت بیمه جدید')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.insurances.index') }}">لیست بیمه</a></li>
    <li class="breadcrumb-item active">ثبت بیمه جدید</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">فرم ثبت بیمه</h3>
                </div>
                <form action="{{ route('admin.insurances.store') }}" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">نام بیمه</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="type">نوع بیمه</label>
                            <select class="form-control" id="type" name="type" required>
                                <option value="basic">پایه</option>
                                <option value="supplementary">تکمیلی</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="discount">درصد تخفیف</label>
                            <input type="number" class="form-control" id="discount"
                                   name="discount" min="0" max="100" required>
                        </div>
                        <div class="form-check">
                            <input type="hidden" name="status" value="0">
                            <input type="checkbox" class="form-check-input"
                                   id="status" name="status" value="1" checked>
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
