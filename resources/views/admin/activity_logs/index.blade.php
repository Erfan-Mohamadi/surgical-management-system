@extends('layouts.master')

@section('title', 'لیست لاگ‌های فعالیت')

@section('breadcrumb')
    <li class="breadcrumb-item active">لیست لاگ‌ها</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">لیست لاگ‌های فعالیت</h3>
                </div>
                <div class="card-body">

                    <form action="{{ route('admin.activity_logs.index') }}" method="GET" class="mb-3">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="causer_name">نام کاربر :</label>
                                <input type="text" name="causer_name" class="form-control"
                                       placeholder="نام کاربر" value="{{ request('causer_name') }}" autocomplete="off">
                            </div>
                            <div class="col-md-3">
                                <label for="log_name">نام لاگ :</label>
                                <select name="log_name" class="form-control">
                                    <option value="">-- همه لاگ‌ها --</option>
                                    <option value="فاکتور" {{ request('log_name') == 'فاکتور' ? 'selected' : '' }}>فاکتور</option>
                                    <option value="پرداخت" {{ request('log_name') == 'پرداخت' ? 'selected' : '' }}>پرداخت</option>
                                    <option value="نقش دکتر" {{ request('log_name') == 'نقش دکتر' ? 'selected' : '' }}>نقش دکتر</option>
                                    <option value="دکتر" {{ request('log_name') == 'دکتر' ? 'selected' : '' }}>دکتر</option>
                                    <option value="بیمه" {{ request('log_name') == 'بیمه' ? 'selected' : '' }}>بیمه</option>
                                    <option value="تخصص‌ها" {{ request('log_name') == 'تخصص‌ها' ? 'selected' : '' }}>تخصص‌ها</option>
                                    <option value="عمل" {{ request('log_name') == 'عمل' ? 'selected' : '' }}>عمل</option>


                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="description">توضیحات :</label>
                                <input type="text" name="description" class="form-control"
                                       placeholder="توضیحات" value="{{ request('description') }}" autocomplete="off">
                            </div>
                            <div class="col-md-1">
                                <label for="start_date">از تاریخ :</label>
                                <input type="text" name="start_date" id="start_date" class="form-control"
                                       value="{{ request('start_date') }}" autocomplete="off">
                            </div>
                            <div class="col-md-1">
                                <label for="end_date">تا تاریخ :</label>
                                <input type="text" name="end_date" id="end_date" class="form-control"
                                       value="{{ request('end_date') }}" autocomplete="off">
                            </div>
                            <div class="col-md-1 align-self-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i> جست‌وجو
                                </button>
                            </div>
                            @if(request()->hasAny(['causer_name', 'log_name', 'description', 'start_date', 'end_date']))
                                <div class="col-md-1 align-self-end">
                                    <a href="{{ route('admin.activity_logs.index') }}" class="btn btn-secondary w-100">
                                        <i class="fas fa-times"></i> پاک‌سازی
                                    </a>
                                </div>
                            @endif
                        </div>
                    </form>

                    <table class="table table-bordered table-hover text-center">
                        <thead class="bg-light">
                        <tr>
                            <th>#</th>
                            <th>کاربر</th>
                            <th>لاگ</th>
                            <th>توضیحات</th>
                            <th>تاریخ</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($activities as $activity)
                            <tr>
                                <td>{{ $activities->firstItem() + $loop->index }}</td>
                                <td>{{ $activity->causer ? $activity->causer->name : '-' }}</td>
                                <td>{{ $activity->log_name }}</td>
                                <td>
                                    {{ $activity->description }} ,
                                    @if($activity->properties->count())
                                        <span class="text-muted">
                                            @foreach($activity->properties as $key => $value)
                                                {{ $key }}: {{ is_array($value) ? json_encode($value) : $value }} <span>|</span>
                                            @endforeach
                                        </span>
                                    @endif
                                </td>
                                <td>{{ verta($activity->created_at)->format('Y/m/d H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">هیچ لاگی یافت نشد</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    @if($activities->hasPages())
                        <div class="card-footer">
                            {{ $activities->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('#start_date').persianDatepicker({ showGregorianDate: true });
        $('#end_date').persianDatepicker({ showGregorianDate: true });

    </script>
@endsection

