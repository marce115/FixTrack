<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Laporan FixTrack</title>
    <style>
        @page {
            margin: 20px 25px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1a202c;
            font-size: 10px;
            line-height: 1.3;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #2b6cb0;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .brand-title {
            font-size: 20px;
            font-weight: bold;
            color: #2b6cb0;
            letter-spacing: -0.5px;
        }
        .brand-subtitle {
            font-size: 9px;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 2px;
        }
        .doc-title {
            text-align: right;
            font-size: 14px;
            font-weight: bold;
            color: #2d3748;
        }
        .doc-meta {
            text-align: right;
            font-size: 9px;
            color: #718096;
            margin-top: 3px;
        }
        .filter-box {
            background-color: #f7fafc;
            border: 1px solid #e2e8f0;
            padding: 8px 12px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 9px;
        }
        .filter-title {
            font-weight: bold;
            color: #4a5568;
            margin-bottom: 4px;
            text-transform: uppercase;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .data-table th {
            background-color: #2d3748;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8px;
            padding: 6px 5px;
            border: 1px solid #2d3748;
            text-align: left;
        }
        .data-table td {
            padding: 5px;
            border: 1px solid #e2e8f0;
            font-size: 8.5px;
            vertical-align: top;
        }
        .data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .badge {
            display: inline-block;
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 7.5px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-tinggi { background-color: #fed7d7; color: #9b2c2c; }
        .badge-sedang { background-color: #feebc8; color: #9c4221; }
        .badge-rendah { background-color: #edf2f7; color: #4a5568; }

        .badge-selesai { background-color: #c6f6d5; color: #22543d; }
        .badge-sedangdiproses { background-color: #feebc8; color: #744210; }
        .badge-pending { background-color: #ebf8ff; color: #2b6cb0; }
        .badge-ditolak { background-color: #fed7d7; color: #9b2c2c; }

        .sign-table {
            width: 100%;
            margin-top: 30px;
            page-break-inside: avoid;
        }
        .sign-box {
            text-align: center;
            width: 35%;
        }
        .sign-space {
            height: 50px;
        }
        .sign-line {
            border-bottom: 1px solid #718096;
            font-weight: bold;
            padding-bottom: 2px;
        }
        .sign-role {
            font-size: 8.5px;
            color: #718096;
            margin-top: 2px;
        }
    </style>
</head>
<body>

    <!-- Header Table -->
    <table class="header-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="vertical-align: middle;">
                <div class="brand-title">FixTrack</div>
                <div class="brand-subtitle">Sistem Pelaporan Kerusakan & Monitoring Aset</div>
            </td>
            <td style="vertical-align: middle;">
                <div class="doc-title">REKAPITULASI LAPORAN ASET</div>
                <div class="doc-meta">
                    Dicetak: {{ $generatedAt }} WIB | Oleh: {{ $generatedBy }}
                </div>
            </td>
        </tr>
    </table>

    <!-- Filter Meta Box -->
    <div class="filter-box">
        <div class="filter-title">Parameter Filter Laporan:</div>
        <table style="width: 100%;">
            <tr>
                <td style="width: 30%;"><strong>Status:</strong> {{ $filters['status'] ?? 'Semua Status' }}</td>
                <td style="width: 35%;"><strong>Kategori:</strong> {{ $selectedCategory->name ?? 'Semua Kategori' }}</td>
                <td style="width: 35%;">
                    <strong>Periode:</strong> 
                    {{ !empty($filters['date_from']) ? \Carbon\Carbon::parse($filters['date_from'])->format('d/m/Y') : 'Awal' }} 
                    s/d 
                    {{ !empty($filters['date_to']) ? \Carbon\Carbon::parse($filters['date_to'])->format('d/m/Y') : 'Sekarang' }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Data Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 12%;">No. Tiket</th>
                <th style="width: 18%;">Nama Barang</th>
                <th style="width: 14%;">Kategori</th>
                <th style="width: 12%;">Lokasi</th>
                <th style="width: 12%;">Jenis Masalah</th>
                <th style="width: 5%; text-align: center;">Jml</th>
                <th style="width: 8%; text-align: center;">Prioritas</th>
                <th style="width: 10%; text-align: center;">Status</th>
                <th style="width: 9%;">Pelapor</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $rep)
                <tr>
                    <td style="font-family: monospace; font-weight: bold; color: #2b6cb0;">
                        {{ $rep->ticket_number }}
                    </td>
                    <td>
                        <strong>{{ $rep->item_name }}</strong>
                    </td>
                    <td>
                        {{ $rep->category->name ?? '-' }}
                    </td>
                    <td>
                        {{ $rep->location }}
                    </td>
                    <td>
                        {{ $rep->problem_type }}
                    </td>
                    <td style="text-align: center; font-family: monospace;">
                        {{ $rep->quantity }}
                    </td>
                    <td style="text-align: center;">
                        <span class="badge badge-{{ strtolower($rep->priority) }}">
                            {{ $rep->priority }}
                        </span>
                    </td>
                    <td style="text-align: center;">
                        @php
                            $statusSlug = strtolower(str_replace(' ', '', $rep->status));
                        @endphp
                        <span class="badge badge-{{ $statusSlug }}">
                            {{ $rep->status }}
                        </span>
                    </td>
                    <td>
                        {{ $rep->user->name ?? '-' }}<br>
                        <span style="color: #718096; font-size: 7px;">{{ $rep->created_at->format('d/m/Y') }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 15px; color: #718096;">
                        Tidak ada catatan laporan yang sesuai dengan kriteria filter.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="font-size: 9px; color: #718096; margin-bottom: 10px;">
        Total Data yang Diekspor: <strong>{{ count($reports) }} Laporan</strong>
    </div>

    <!-- Signatures -->
    <table class="sign-table">
        <tr>
            <td class="sign-box">
                <div>Dibuat oleh,</div>
                <div class="sign-space"></div>
                <div class="sign-line">{{ $generatedBy }}</div>
                <div class="sign-role">Administrator FixTrack</div>
            </td>
            <td style="width: 30%;"></td>
            <td class="sign-box">
                <div>Mengetahui,</div>
                <div class="sign-space"></div>
                <div class="sign-line">Pimpinan Operasional</div>
                <div class="sign-role">Manager Operasi & Fasilitas</div>
            </td>
        </tr>
    </table>

</body>
</html>
