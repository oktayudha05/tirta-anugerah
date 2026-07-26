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
        Schema::create('pembayaran_pemasangans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warga_id')->constrained('wargas')->cascadeOnDelete();
            $table->integer('total_biaya');                              // Total biaya pemasangan saat didaftarkan
            $table->integer('total_dibayar')->default(0);               // Akumulasi semua pembayaran (DP + cicilan)
            $table->string('status')->default('belum_lunas');           // 'belum_lunas' atau 'lunas'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_pemasangans');
    }
};
