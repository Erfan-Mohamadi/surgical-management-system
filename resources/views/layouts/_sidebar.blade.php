<!--begin::Sidebar-->
<aside id="aside-bar" class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="{{route('admin.dashboard')}}" class="brand-link">
            <!--begin::Brand Image-->
            <img
                src="{{ asset('assets/img/AdminLTELogo.png') }}"
                alt="AdminLTE Logo"
                class="brand-image opacity-75 shadow"
            />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">AdminLTE 4</span>
            <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand-->
    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul
                class="nav sidebar-menu flex-column"
                data-lte-toggle="treeview"
                role="menu"
                data-accordion="false"
            >
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>داشبورد</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a  href="{{ route('admin.specialities.index') }} " class="nav-link">
                        <i class="nav-icon bi bi-box"></i>
                        <p>تخصص ها</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.doctor_roles.index') }} " class="nav-link">
                        <i class="nav-icon bi bi-clipboard2"></i>
                        <p>نقش دکتر</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.doctors.index') }}" class="nav-link">
                        <i class="nav-icon bi-person-heart"></i>
                        <p>دکتر</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.insurances.index') }}" class="nav-link">
                        <i class="nav-icon bi bi-postcard-heart-fill"></i>
                        <p>بیمه ها</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.operations.index') }}" class="nav-link">
                        <i class="nav-icon bi bi-activity"></i>
                        <p>عمل ها</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.surgeries.index') }}" class="nav-link">
                        <i class="nav-icon bi bi-h-square"></i>
                        <p>عمل‌های جراهی</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.invoices.create') }}" class="nav-link">
                        <i class="nav-icon bi bi-cash"></i>
                        <p>پرداخت به پزشک</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.invoices.index') }}" class="nav-link">
                        <i class="nav-icon bi-clipboard-data"></i>
                        <p>صورت حساب‌ها</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.reports.doctor.index') }}" class="nav-link">
                        <i class="nav-icon bi bi-credit-card-fill"></i>
                        <p>گزارش پزشکان</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.activity_logs.index') }}" class="nav-link">
                        <i class="nav-icon bi bi-person-circle"></i>
                        <p>لاگ ها</p>
                    </a>
                </li>
            </ul>
            <!--end::Sidebar Menu-->
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->
