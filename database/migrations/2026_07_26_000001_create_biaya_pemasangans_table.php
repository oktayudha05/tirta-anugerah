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
        Schema::create('biaya_pemasangans', function (Blueprint $table) {
            $table->id();
            $table->integer('biaya');           // Nominal biaya pemasangan (misal: 2000000)
            $table->string('berlaku_mulai');    // Format 'YYYY-MM', kapan harga ini mulai berlaku
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biaya_pemasangans');
    }
};
