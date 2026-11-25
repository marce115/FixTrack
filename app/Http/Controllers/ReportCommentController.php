<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Report;
use App\Models\ReportComment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ReportCommentController extends Controller
{
    public function store(Request $request, Report $report): RedirectResponse
    {
        $currentUser = Auth::user();

        // Only Admin or the report creator can comment
        if (! $currentUser->isAdmin() && $report->user_id !== $currentUser->id) {
            abort(403, 'Anda tidak berhak memberikan komentar pada laporan ini.');
        }

        $validated = $request->validate([
            'comment' => ['required', 'string', 'max:1000'],
        ], [
            'comment.required' => 'Pesan komentar tidak boleh kosong.',
            'comment.max' => 'Pesan komentar maksimal 1000 karakter.',
        ]);

        $comment = ReportComment::create([
            'report_id' => $report->id,
            'user_id' => $currentUser->id,
            'comment' => $validated['comment'],
        ]);

        $preview = Str::limit($comment->comment, 60);

        // Notify other parties
        if ($currentUser->isAdmin()) {
            // Admin commented -> notify report owner if not self
            if ($report->user_id !== $currentUser->id) {
                Notification::create([
                    'user_id' => $report->user_id,
                    'title' => 'Tanggapan Admin pada '.$report->ticket_number,
                    'message' => $currentUser->name.': "'.$preview.'"',
                    'type' => 'new_comment',
                    'link' => route('reports.show', $report).'#comment-'.$comment->id,
                ]);
            }
        } else {
            // User commented -> notify all admins
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => 'Komentar Baru: '.$report->ticket_number,
                    'message' => $currentUser->name.' ('.$report->item_name.'): "'.$preview.'"',
                    'type' => 'new_comment',
                    'link' => route('reports.show', $report).'#comment-'.$comment->id,
                ]);
            }
        }

        return redirect()->to(route('reports.show', $report).'#discussion')
            ->with('success', 'Pesan diskusi berhasil dikirim.');
    }
}
