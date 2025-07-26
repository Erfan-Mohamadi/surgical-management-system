<?php

namespace App\Http\Middleware;

use App\Models\DoctorRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureDctorRoleSum
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (DoctorRole::getSum() !== 100) {
            return redirect()
                ->route('admin.doctor_roles.index')
                ->with('error', 'سهم‌ها در لیست نقش‌های پزشکی دچار مشکل هستند، لطفاً اصلاح کنید!');
        }

        return $next($request);
    }
}
