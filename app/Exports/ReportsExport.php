<?php

namespace App\Exports;

use App\Models\Report;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportsExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
{
    public function __construct(protected array $filters = []) {}

    public function collection(): Enumerable
    {
        $query = Report::with(['user', 'category']);

        if (! empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (! empty($this->filters['category_id'])) {
            $query->where('category_id', $this->filters['category_id']);
        }

        if (! empty($this->filters['priority'])) {
            $query->where('priority', $this->filters['priority']);
        }

        if (! empty($this->filters['date_from'])) {
            $query->whereDate('created_at', '>=', $this->filters['date_from']);
        }

        if (! empty($this->filters['date_to'])) {
            $query->whereDate('created_at', '<=', $this->filters['date_to']);
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'No. Tiket',
            'Nama Barang',
            'Kategori',
            'Lokasi / Departemen',
            'Jenis Masalah',
            'Jumlah',
            'Prioritas',
            'Status',
            'Pelapor',
            'Catatan Penanganan',
            'Tanggal Lapor',
        ];
    }

    public function map($report): array
    {
        return [
            $report->ticket_number,
            $report->item_name,
            $report->category->name ?? '-',
            $report->location,
            $report->problem_type,
            $report->quantity,
            $report->priority,
            $report->status,
            $report->user->name ?? '-',
            $report->handling_notes ?? '-',
            $report->created_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
