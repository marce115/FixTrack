<?php

namespace App\Http\Controllers;

use App\Exports\ReportsExport;
use App\Models\Category;
use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class ExportController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->get();
        $filters = $request->only(['status', 'category_id', 'priority', 'date_from', 'date_to']);

        $query = Report::with(['user', 'category']);

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (! empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }
        if (! empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }
        if (! empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (! empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        $previewCount = $query->count();
        $recentPreview = $query->latest()->take(5)->get();

        return view('exports.index', compact('categories', 'filters', 'previewCount', 'recentPreview'));
    }

    public function pdf(Request $request): Response
    {
        $filters = $request->only(['status', 'category_id', 'priority', 'date_from', 'date_to']);

        $query = Report::with(['user', 'category']);

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (! empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }
        if (! empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }
        if (! empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (! empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        $reports = $query->latest()->get();
        $selectedCategory = ! empty($filters['category_id']) ? Category::find($filters['category_id']) : null;
        $generatedAt = Carbon::now()->isoFormat('D MMMM Y, HH:mm');
        $generatedBy = Auth::user()->name;

        $pdf = Pdf::loadView('exports.pdf', compact(
            'reports',
            'filters',
            'selectedCategory',
            'generatedAt',
            'generatedBy'
        ))->setPaper('a4', 'landscape');

        $filename = 'rekap-laporan-fixtrack-'.date('Ymd-His').'.pdf';

        return $pdf->download($filename);
    }

    public function excel(Request $request): BinaryFileResponse
    {
        $filters = $request->only(['status', 'category_id', 'priority', 'date_from', 'date_to']);
        $filename = 'rekap-laporan-fixtrack-'.date('Ymd-His').'.xlsx';

        return Excel::download(new ReportsExport($filters), $filename);
    }
}
