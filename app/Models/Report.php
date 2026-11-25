<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'user_id',
        'item_id',
        'item_name',
        'inventory_code',
        'category_id',
        'location',
        'problem_type',
        'description',
        'quantity',
        'priority',
        'photo_path',
        'completion_photo_path',
        'status',
        'handling_notes',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(ReportHistory::class, 'report_id')->latest();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(ReportComment::class, 'report_id')->oldest();
    }

    public static function generateTicketNumber(): string
    {
        $prefix = 'TKT-'.date('Ymd').'-';
        $latest = self::where('ticket_number', 'LIKE', $prefix.'%')
            ->orderBy('id', 'desc')
            ->first();

        if ($latest) {
            $lastNumber = (int) substr($latest->ticket_number, -4);
            $newNumber = str_pad((string) ($lastNumber + 1), 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix.$newNumber;
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                    ->orWhere('item_name', 'like', "%{$search}%")
                    ->orWhere('inventory_code', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($qu) use ($search) {
                        $qu->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (! empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (! empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query;
    }

    public function getPriorityBadgeColorAttribute(): string
    {
        return match ($this->priority) {
            'Tinggi' => 'bg-rose-500/10 text-rose-400 border border-rose-500/20',
            'Sedang' => 'bg-amber-500/10 text-amber-400 border border-amber-500/20',
            'Rendah' => 'bg-blue-500/10 text-blue-400 border border-blue-500/20',
            default => 'bg-slate-500/10 text-slate-400 border border-slate-500/20',
        };
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            'Selesai' => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20',
            'Sedang Diproses' => 'bg-amber-500/10 text-amber-400 border border-amber-500/20',
            'Ditolak' => 'bg-rose-500/10 text-rose-400 border border-rose-500/20',
            'Pending' => 'bg-blue-500/10 text-blue-400 border border-blue-500/20',
            default => 'bg-slate-500/10 text-slate-400 border border-slate-500/20',
        };
    }
}
