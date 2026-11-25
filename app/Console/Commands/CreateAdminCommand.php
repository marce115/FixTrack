<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fixtrack:create-admin
                            {--name= : Nama lengkap administrator}
                            {--email= : Alamat email administrator}
                            {--password= : Kata sandi administrator (minimal 8 karakter)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membuat atau memperbarui akun Administrator utama FixTrack';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $name = $this->option('name')
            ?? config('fixtrack.admin.name')
            ?? $this->ask('Nama Administrator', 'Administrator IT');

        $email = $this->option('email')
            ?? config('fixtrack.admin.email')
            ?? $this->ask('Alamat Email Administrator');

        $password = $this->option('password')
            ?? config('fixtrack.admin.password')
            ?? $this->secret('Kata Sandi Administrator (minimal 8 karakter)');

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $admin = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        $this->info("Akun Administrator berhasil disiapkan untuk: {$admin->email}");

        return self::SUCCESS;
    }
}
