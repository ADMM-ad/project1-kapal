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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->enum('allcrew', ['ya', 'tidak'])->nullable();
            $table->dateTime('tanggal_mulai');
            $table->dateTime('deadline')->nullable();
            $table->dateTime('tanggal_dikerjakan')->nullable();
            $table->dateTime('tanggal_selesai')->nullable();
            $table->enum('status', ['belum', 'proses', 'selesai'])->default('belum');
            $table->timestamps();
        });
        Schema::create('detail_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
          Schema::dropIfExists('tasks');
          Schema::dropIfExists('detail_tasks');
    }
};
