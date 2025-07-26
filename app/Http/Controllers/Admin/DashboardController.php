<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;

class DashboardController extends Controller
{
    // =============================================
    // DASHBOARD DISPLAY METHODS
    // =============================================

    /**
     * Display admin dashboard with upcoming cheque payments
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get upcoming cheque payments with related invoice and doctor data
        $upcomingCheques = Payment::upcomingCheques()
            ->with('invoice.doctor')
            ->get();

        // Return dashboard view with payment data
        return view('admin.dashboard', compact('upcomingCheques'));
    }
}
