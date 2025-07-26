<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSurgeryDates
{
    public function handle(Request $request, Closure $next)
    {
        $surgeriedDate = $request->input('surgeried_at');
        $releasedDate = $request->input('released_at');

        if (date($surgeriedDate) > date($releasedDate)) {
            return redirect()->back()
                ->withErrors(['surgeryDates' => 'تاریخ عمل نمی‌تواند بعد از تاریخ ترخیص باشد.'])
                ->withInput();
        }

        return $next($request);
    }
}
