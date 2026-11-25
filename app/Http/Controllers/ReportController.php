<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Notification;
use App\Models\Report;
use App\Models\ReportHistory;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        $query = Report::with(['user', 'category', 'item']);

        // User role only sees their own reports
        if ($user->isUser()) {
            $query->where('user_id', $user->id);
        }

        // Apply filters
        $filters = $request->only([
            'search',
            'status',
            'priority',
            'category_id',
            'date_from',
            'date_to',
        ]);

        $query->filter($filters);

        // Sorting
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        $allowedSorts = ['ticket_number', 'item_name', 'created_at', 'priority', 'status'];

        if (in_array($sortField, $allowedSorts, true)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        } else {
            $query->latest();
        }

        $reports = $query->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('reports.index', compact('reports', 'categories', 'filters'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        $items = Item::with('category')->orderBy('name')->get();
        $autoTicket = Report::generateTicketNumber();
        $problemTypes = config('fixtrack.problem_types');

        return view('reports.create', compact('categories', 'items', 'autoTicket', 'problemTypes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'item_id' => ['nullable', 'exists:items,id'],
            'item_name' => ['required', 'string', 'max:255'],
            'inventory_code' => ['nullable', 'string', 'max:100'],
            'category_id' => ['required', 'exists:categories,id'],
            'location' => ['required', 'string', 'max:255'],
            'problem_type' => ['required', 'string', Rule::in(config('fixtrack.problem_types'))],
            'description' => ['required', 'string', 'min:5'],
            'quantity' => ['required', 'integer', 'min:1', 'max:9999'],
            'priority' => ['required', 'in:Rendah,Sedang,Tinggi'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ], [
            'item_name.required' => 'Nama barang / aset wajib diisi.',
            'category_id.required' => 'Kategori aset wajib dipilih.',
            'location.required' => 'Lokasi atau departemen wajib diisi.',
            'problem_type.required' => 'Jenis masalah wajib dipilih.',
            'problem_type.in' => 'Jenis masalah tidak valid.',
            'description.required' => 'Deskripsi masalah wajib diisi.',
            'description.min' => 'Deskripsi masalah minimal 5 karakter.',
            'quantity.required' => 'Jumlah aset bermasalah wajib diisi.',
            'priority.required' => 'Tingkat prioritas wajib dipilih.',
            'photo.image' => 'File bukti harus berupa gambar.',
            'photo.max' => 'Ukuran foto maksimal 5MB.',
        ]);

        return DB::transaction(function () use ($request, $validated) {
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('reports', 'public');
            }

            $ticketNumber = Report::generateTicketNumber();

            $report = Report::create([
                'ticket_number' => $ticketNumber,
                'user_id' => Auth::id(),
                'item_id' => $validated['item_id'] ?? null,
                'item_name' => $validated['item_name'],
                'inventory_code' => $validated['inventory_code'] ?? null,
                'category_id' => $validated['category_id'],
                'location' => $validated['location'],
                'problem_type' => $validated['problem_type'],
                'description' => $validated['description'],
                'quantity' => $validated['quantity'],
                'priority' => $validated['priority'],
                'photo_path' => $photoPath,
                'status' => 'Pending',
            ]);

            ReportHistory::create([
                'report_id' => $report->id,
                'user_id' => Auth::id(),
                'action' => 'Pembuatan Laporan',
                'old_status' => null,
                'new_status' => 'Pending',
                'notes' => 'Laporan tiket diajukan oleh '.Auth::user()->name.'.',
            ]);

            // Notify all admins of the new report
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $adminUser) {
                Notification::create([
                    'user_id' => $adminUser->id,
                    'title' => 'Laporan Baru: '.$ticketNumber,
                    'message' => Auth::user()->name.' melaporkan: '.$report->item_name.' ('.$report->problem_type.').',
                    'type' => 'new_report',
                    'link' => route('reports.show', $report),
                ]);
            }

            return redirect()->route('reports.show', $report)
                ->with('success', 'Laporan berhasil dibuat dengan Nomor Tiket: '.$ticketNumber);
        });
    }

    public function show(Report $report): View
    {
        $user = Auth::user();

        // Non-admin can only view their own reports
        if ($user->isUser() && $report->user_id !== $user->id) {
            abort(403, 'Anda tidak berhak melihat laporan ini.');
        }

        $report->load([
            'user',
            'item',
            'category',
            'histories.user',
            'comments.user',
        ]);

        return view('reports.show', compact('report'));
    }

    public function updateStatus(Request $request, Report $report): RedirectResponse
    {
        if (! Auth::user()->isAdmin()) {
            abort(403, 'Hanya Admin yang dapat memperbarui status laporan.');
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in(config('fixtrack.statuses'))],
            'priority' => ['nullable', 'in:Rendah,Sedang,Tinggi'],
            'handling_notes' => ['nullable', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'completion_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ], [
            'status.required' => 'Status laporan wajib dipilih.',
            'completion_photo.image' => 'File bukti selesai harus berupa gambar.',
            'completion_photo.max' => 'Ukuran foto bukti selesai maksimal 5MB.',
        ]);

        $handlingNotes = $validated['handling_notes'] ?? ($validated['notes'] ?? null);

        // Handle completion photo upload
        $completionPhotoPath = $report->completion_photo_path;
        if ($request->hasFile('completion_photo')) {
            if ($report->completion_photo_path) {
                Storage::disk('public')->delete($report->completion_photo_path);
            }
            $completionPhotoPath = $request->file('completion_photo')->store('reports/completion', 'public');
        }

        DB::transaction(function () use ($report, $validated, $handlingNotes, $completionPhotoPath) {
            $oldStatus = $report->status;
            $newStatus = $validated['status'];

            $updateData = [
                'status' => $newStatus,
                'handling_notes' => $handlingNotes ?? $report->handling_notes,
                'completion_photo_path' => $completionPhotoPath,
            ];

            if (! empty($validated['priority'])) {
                $updateData['priority'] = $validated['priority'];
            }

            $report->update($updateData);

            ReportHistory::create([
                'report_id' => $report->id,
                'user_id' => Auth::id(),
                'action' => 'Perubahan Status',
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'notes' => $handlingNotes ?? ('Status diperbarui menjadi '.$newStatus.' oleh Admin '.Auth::user()->name),
            ]);

            // Notify report owner
            if ($report->user_id !== Auth::id()) {
                Notification::create([
                    'user_id' => $report->user_id,
                    'title' => 'Status Tiket: '.$report->ticket_number,
                    'message' => 'Status laporan Anda diperbarui menjadi "'.$newStatus.'" oleh Admin.',
                    'type' => 'status_updated',
                    'link' => route('reports.show', $report),
                ]);
            }
        });

        return back()->with('success', 'Status laporan '.$report->ticket_number.' berhasil diperbarui.');
    }

    public function destroy(Report $report): RedirectResponse
    {
        if (! Auth::user()->isAdmin()) {
            abort(403, 'Hanya Admin yang dapat menghapus laporan.');
        }

        if ($report->photo_path) {
            Storage::disk('public')->delete($report->photo_path);
        }

        if ($report->completion_photo_path) {
            Storage::disk('public')->delete($report->completion_photo_path);
        }

        $ticket = $report->ticket_number;
        $report->delete();

        return redirect()->route('reports.index')->with('success', 'Laporan '.$ticket.' berhasil dihapus.');
    }
}
