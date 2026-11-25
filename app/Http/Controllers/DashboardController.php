<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        }

        return $this->userDashboard($user);
    }

    private function adminDashboard(): View
    {
        $totalReports = Report::count();
        $pendingReports = Report::where('status', 'Pending')->count();
        $inProgressReports = Report::where('status', 'Sedang Diproses')->count();
        $completedReports = Report::where('status', 'Selesai')->count();
        $problematicAssetsCount = (int) Report::sum('quantity');

        $recentReports = Report::with(['user', 'category'])
            ->latest()
            ->take(6)
            ->get();

        $statusCounts = [
            'Pending' => $pendingReports,
            'Sedang Diproses' => $inProgressReports,
            'Selesai' => $completedReports,
        ];

        return view('dashboard.admin', compact(
            'totalReports',
            'pendingReports',
            'inProgressReports',
            'completedReports',
            'problematicAssetsCount',
            'recentReports',
            'statusCounts'
        ));
    }

    private function userDashboard($user): View
    {
        $reportsQuery = Report::where('user_id', $user->id);

        $totalReports = (clone $reportsQuery)->count();
        $pendingReports = (clone $reportsQuery)->where('status', 'Pending')->count();
        $inProgressReports = (clone $reportsQuery)->where('status', 'Sedang Diproses')->count();
        $completedReports = (clone $reportsQuery)->where('status', 'Selesai')->count();

        $recentReports = (clone $reportsQuery)
            ->with('category')
            ->latest()
            ->take(6)
            ->get();

        return view('dashboard.user', compact(
            'totalReports',
            'pendingReports',
            'inProgressReports',
            'completedReports',
            'recentReports'
        ));
    }
}
