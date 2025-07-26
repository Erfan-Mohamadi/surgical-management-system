@extends('layouts.master')

@section('title', 'ویرایش نقش پزشکی')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.doctor_roles.index') }}">لیست نقش‌های پزشکی</a></li>
    <li class="breadcrumb-item active">ویرایش نقش</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">ویرایش نقش</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.doctor_roles.update', $doctorRole->id) }}" method="post">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="title">عنوان نقش</label>
                            <input type="text" name="title" class="form-control"
                                   value="{{ old('title', $doctorRole->title) }}" required>
                        </div>

                        <div class="form-group mt-3">
                            <label for="quota">سهمیه (درصد)</label>
                            <input type="number" name="quota" class="form-control"
                                   min="0" max="100" value="{{ old('quota', $doctorRole->quota) }}" required autofocus>
                            @error('quota')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                            @if($availableQuota = 100 - \App\Models\DoctorRole::where('id', '!=', $doctorRole->id)->sum('quota'))
                                <small class="text-muted">سهمیه قابل اختصاص: {{ $availableQuota }}%</small>
                            @endif
                        </div>

                        <div class="form-check mt-3">
                            <input type="hidden" name="required" value="0"> <!-- Default value when unchecked -->
                            <input type="checkbox" name="required" id="required" class="form-check-input"
                                   value="1" @checked(old('required', $doctorRole->required))>
                            <label for="required" class="form-check-label">نقش ضروری</label>
                        </div>

                        <div class="form-check mt-3">
                            <input type="hidden" name="status" value="0"> <!-- Default value when unchecked -->
                            <input type="checkbox" name="status" id="status" class="form-check-input"
                                   value="1" @checked(old('status', $doctorRole->status))>
                            <label for="status" class="form-check-label">فعال</label>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-warning">ذخیره تغییرات</button>
                            <a href="{{ route('admin.doctor_roles.index') }}" class="btn btn-secondary">انصراف</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
