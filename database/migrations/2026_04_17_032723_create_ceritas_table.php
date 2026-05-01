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
        Schema::create('ceritas', function (Blueprint $table) {
            $table->id();
            // 👇 Tinggal tambahin ->unique() di ujungnya sini brow
            $table->string('judul')->unique();
            $table->text('deskripsi_singkat');
            $table->longText('isi_cerita');
            // Foreign key ke tabel users (publisher)
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('tanggal_rilis');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ceritas');
    }
};