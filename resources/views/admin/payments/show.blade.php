<h3>اطلاعات فاکتور</h3>
<p>پزشک: {{ $invoice->doctor->name }}</p>
<p>مبلغ کل: {{ number_format($invoice->amount) }} تومان</p>

<h4>لیست جراحی‌ها</h4>
<table class="table">
    <thead>
    <tr>
        <th>نام بیمار</th>
        <th>عمل</th>
        <th>مبلغ</th>
    </tr>
    </thead>
    <tbody>
    @foreach($invoice->doctorSurgeries as $ds)
        <tr>
            <td>{{ $ds->surgery->patient_name }}</td>
            <td>{{ $ds->role->title }}</td>
            <td>{{ number_format($ds->amount) }} تومان</td>
        </tr>
    @endforeach
    </tbody>
</table>

<hr>

<h4>پرداخت‌ها</h4>
<table class="table">
    <thead>
    <tr>
        <th>مبلغ</th>
        <th>نوع پرداخت</th>
        <th>تاریخ سررسید</th>
        <th>وضعیت</th>
    </tr>
    </thead>
    <tbody>
    @foreach($invoice->payments as $payment)
        <tr>
            <td>{{ number_format($payment->amount) }}</td>
            <td>{{ $payment->pay_type == 'cash' ? 'نقدی' : 'چک' }}</td>
            <td>{{ $payment->due_date ?? '-' }}</td>
            <td>{{ $payment->status ? 'پرداخت شده' : 'در انتظار' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<hr>

<h4>افزودن پرداخت جدید</h4>
<form action="{{ route('admin.payments.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
    <div class="form-group">
        <label>مبلغ</label>
        <input type="number" name="amount" class="form-control" required>
    </div>
    <div class="form-group">
        <label>نوع پرداخت</label>
        <select name="pay_type" class="form-control" required>
            <option value="cash">نقدی</option>
            <option value="cheque">چک</option>
        </select>
    </div>
    <div class="form-group">
        <label>تاریخ سررسید (برای چک)</label>
        <input type="date" name="due_date" class="form-control">
    </div>
    <div class="form-group">
        <label>رسید (اختیاری)</label>
        <input type="file" name="receipt" class="form-control">
    </div>
    <div class="form-group">
        <label>توضیحات</label>
        <textarea name="description" class="form-control"></textarea>
    </div>
    <button type="submit" class="btn btn-success mt-3">افزودن پرداخت</button>
</form>
