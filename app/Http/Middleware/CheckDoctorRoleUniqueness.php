<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckDoctorRoleUniqueness
{
    public function handle(Request $request, Closure $next)
    {
        $doctorRoles = $request->input('doctor_roles', []);
        $doctorIds = array_values($doctorRoles);

        if (count($doctorIds) !== count(array_unique($doctorIds))) {
            return redirect()->back()
                ->withErrors(['doctor_roles' => 'یک پزشک نمی‌تواند بیش از یک نقش در یک جراحی داشته باشد.'])
                ->withInput();
        }

        return $next($request);
    }
}
