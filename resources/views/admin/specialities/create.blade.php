@extends('layouts.master')

@section('title', 'ثبت تخصص جدید')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.specialities.index') }}">لیست تخصص‌ها</a></li>
    <li class="breadcrumb-item active">ثبت تخصص جدید</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">ثبت تخصص جدید</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.specialities.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">عنوان تخصص</label>
                                    <input type="text" id="title" name="title" class="form-control"
                                           value="{{ old('title') }}" required autofocus autocomplete="off">
                                    @error('title')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check pt-4">
                                    <input class="form-check-input" name="status" type="checkbox"
                                           value="1" id="status" @checked(old('status', true))>
                                    <label class="form-check-label" for="status">
                                        فعال
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col text-center">
                                <button type="submit" class="btn btn-primary">ثبت تخصص</button>
                                <a href="{{ route('admin.specialities.index') }}" class="btn btn-secondary">انصراف</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
