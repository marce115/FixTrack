<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('item_id')->nullable()->constrained('items')->nullOnDelete();
            $table->string('item_name');
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('location');
            $table->enum('problem_type', ['Rusak', 'Hilang', 'Kekurangan Stok']);
            $table->text('description');
            $table->integer('quantity')->default(1);
            $table->enum('priority', ['Rendah', 'Sedang', 'Tinggi'])->default('Sedang');
            $table->string('photo_path')->nullable();
            $table->string('completion_photo_path')->nullable();
            $table->enum('status', ['Pending', 'Sedang Diproses', 'Selesai', 'Ditolak'])->default('Pending');
            $table->text('handling_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
