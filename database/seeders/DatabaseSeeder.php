<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Initial Administrator Account (configured via .env, no hardcoded credentials)
        $adminEmail = config('fixtrack.admin.email');
        $adminPassword = config('fixtrack.admin.password');
        $adminName = config('fixtrack.admin.name', 'Administrator IT');

        if (! empty($adminEmail) && ! empty($adminPassword)) {
            User::updateOrCreate(
                ['email' => $adminEmail],
                [
                    'name' => $adminName,
                    'password' => Hash::make($adminPassword),
                    'role' => 'admin',
                    'status' => 'active',
                ]
            );
        }

        // 2. IT Industry Asset Categories
        $catHardware = Category::updateOrCreate(
            ['name' => 'Hardware Komputer'],
            ['description' => 'Desktop PC, Laptop, Monitor, Keyboard, Mouse, Printer, Scanner, UPS.']
        );

        $catNetwork = Category::updateOrCreate(
            ['name' => 'Network & Infrastructure'],
            ['description' => 'Router, Switch, Access Point, Modem, Server, Rack Server, Firewall, Kabel LAN, Fiber Optic.']
        );

        $catStorage = Category::updateOrCreate(
            ['name' => 'Storage & Peripheral'],
            ['description' => 'External HDD, SSD, Flashdisk, RAM, VGA, Hard Disk, Webcam, Headset.']
        );

        $catSoftware = Category::updateOrCreate(
            ['name' => 'Software & Lisensi'],
            ['description' => 'Sistem Operasi, Antivirus, Software Perkantoran, Software Industri, Lisensi Aplikasi.']
        );

        // 3. IT Asset Master Catalog (Initial Reference Assets)
        $itAssets = [
            // A. Hardware Komputer
            [
                'item_code' => 'IT-HW-001',
                'name' => 'Desktop PC Workstation',
                'category_id' => $catHardware->id,
                'location' => 'Ruang Operasional IT',
                'stock_quantity' => 10,
                'condition' => 'Baik',
                'description' => 'Komputer desktop standar kerja departemen.',
            ],
            [
                'item_code' => 'IT-HW-002',
                'name' => 'Laptop Bisnis',
                'category_id' => $catHardware->id,
                'location' => 'Gudang Aset IT',
                'stock_quantity' => 8,
                'condition' => 'Baik',
                'description' => 'Laptop operasional untuk mobilitas karyawan dan tim teknis.',
            ],
            [
                'item_code' => 'IT-HW-003',
                'name' => 'Monitor LED 24 Inch',
                'category_id' => $catHardware->id,
                'location' => 'Gudang Aset IT',
                'stock_quantity' => 12,
                'condition' => 'Baik',
                'description' => 'Monitor display Full HD untuk workstation.',
            ],
            [
                'item_code' => 'IT-HW-004',
                'name' => 'Printer LaserJet Jaringan',
                'category_id' => $catHardware->id,
                'location' => 'Area Administrasi Lantai 1',
                'stock_quantity' => 4,
                'condition' => 'Baik',
                'description' => 'Printer laser monokrom dengan konektivitas LAN.',
            ],
            [
                'item_code' => 'IT-HW-005',
                'name' => 'UPS 1200VA',
                'category_id' => $catHardware->id,
                'location' => 'Ruang Server & Workstation',
                'stock_quantity' => 6,
                'condition' => 'Baik',
                'description' => 'Cadangan daya listrik untuk melindungi perangkat dari mati lampu mendadak.',
            ],

            // B. Network & Infrastructure
            [
                'item_code' => 'IT-NW-001',
                'name' => 'Router Enterprise',
                'category_id' => $catNetwork->id,
                'location' => 'Ruang Server Utama',
                'stock_quantity' => 3,
                'condition' => 'Baik',
                'description' => 'Router utama pengatur lalu lintas jaringan kantor dan VPN.',
            ],
            [
                'item_code' => 'IT-NW-002',
                'name' => 'Managed Switch 24-Port Gigabit',
                'category_id' => $catNetwork->id,
                'location' => 'Rack Server Lantai 1 & 2',
                'stock_quantity' => 5,
                'condition' => 'Baik',
                'description' => 'Switch distribusi jaringan lokal dengan dukungan VLAN.',
            ],
            [
                'item_code' => 'IT-NW-003',
                'name' => 'Access Point Wi-Fi 6',
                'category_id' => $catNetwork->id,
                'location' => 'Area Kerja & Ruang Meeting',
                'stock_quantity' => 8,
                'condition' => 'Baik',
                'description' => 'Perangkat pemancar jaringan nirkabel PoE.',
            ],
            [
                'item_code' => 'IT-NW-004',
                'name' => 'Server Rackmount & Firewall',
                'category_id' => $catNetwork->id,
                'location' => 'Data Center / Ruang Server',
                'stock_quantity' => 2,
                'condition' => 'Baik',
                'description' => 'Infrastruktur server aplikasi internal dan keamanan jaringan.',
            ],

            // C. Storage & Peripheral
            [
                'item_code' => 'IT-ST-001',
                'name' => 'SSD NVMe 1TB',
                'category_id' => $catStorage->id,
                'location' => 'Gudang Sparepart IT',
                'stock_quantity' => 6,
                'condition' => 'Baik',
                'description' => 'Media penyimpanan internal berkecepatan tinggi.',
            ],
            [
                'item_code' => 'IT-ST-002',
                'name' => 'External HDD 2TB',
                'category_id' => $catStorage->id,
                'location' => 'Ruang Operasional IT',
                'stock_quantity' => 4,
                'condition' => 'Baik',
                'description' => 'Penyimpanan eksternal untuk backup data berkala.',
            ],
            [
                'item_code' => 'IT-ST-003',
                'name' => 'Webcam & Headset Conference',
                'category_id' => $catStorage->id,
                'location' => 'Gudang Aset IT',
                'stock_quantity' => 10,
                'condition' => 'Baik',
                'description' => 'Perangkat pendukung rapat daring dan komunikasi tim.',
            ],

            // D. Software & Lisensi
            [
                'item_code' => 'IT-SW-001',
                'name' => 'Lisensi Sistem Operasi & Antivirus',
                'category_id' => $catSoftware->id,
                'location' => 'Repositori Lisensi IT',
                'stock_quantity' => 25,
                'condition' => 'Baik',
                'description' => 'Lisensi resmi OS workstation dan proteksi endpoint antivirus.',
            ],
            [
                'item_code' => 'IT-SW-002',
                'name' => 'Software Perkantoran & Lisensi Aplikasi',
                'category_id' => $catSoftware->id,
                'location' => 'Repositori Lisensi IT',
                'stock_quantity' => 20,
                'condition' => 'Baik',
                'description' => 'Paket aplikasi produktivitas perkantoran dan software industri.',
            ],
        ];

        foreach ($itAssets as $asset) {
            Item::updateOrCreate(
                ['item_code' => $asset['item_code']],
                $asset
            );
        }
    }
}
