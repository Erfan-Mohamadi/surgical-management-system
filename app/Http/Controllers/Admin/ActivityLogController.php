<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    // =============================================
    // MAIN ACTION METHODS
    // =============================================

    public function index(Request $request)
    {
        // Get filter parameters from request
        $causerName = $request->input('causer_name');
        $logName = $request->input('log_name');
        $description = $request->input('description');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // =============================================
        // ACTIVITY QUERY WITH FILTERS
        // =============================================
        $activities = Activity::with('causer')
            // Filter by causer name
            ->when($causerName, function (Builder $query) use ($causerName) {
                $query->whereHas('causer', function ($q) use ($causerName) {
                    $q->where('name', 'like', '%'.$causerName.'%');
                });
            })
            // Filter by log name
            ->when($logName, function (Builder $query) use ($logName) {
                $query->where('log_name', $logName);
            })
            // Filter by description
            ->when($description, function (Builder $query) use ($description) {
                $query->where('description', 'like', '%'.$description.'%');
            })
            // Filter by start date
            ->when($startDate, function (Builder $query) use ($startDate) {
                $query->whereDate('created_at', '>=', $startDate);
            })
            // Filter by end date
            ->when($endDate, function (Builder $query) use ($endDate) {
                $query->whereDate('created_at', '<=', $endDate);
            })
            // Sorting and pagination
            ->latest()
            ->paginate(30)
            ->appends($request->query());

        // =============================================
        // VIEW RENDERING
        // =============================================
        return view('admin.activity_logs.index', compact('activities'));
    }
}
