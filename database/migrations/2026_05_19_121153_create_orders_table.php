<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('product_snapshot');
            $table->unsignedBigInteger('harga_satuan');
            $table->unsignedInteger('jumlah');
            $table->unsignedBigInteger('total_harga');

            $table->string('nama_customer');
            $table->text('alamat');
            $table->string('email');
            $table->string('whatsapp')->nullable();

            $table->string('metode_pembayaran');
            $table->text('catatan')->nullable();
            $table->string('bukti_pembayaran');

            $table->enum('status', ['diproses', 'dikirim', 'selesai'])
                ->default('diproses');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};