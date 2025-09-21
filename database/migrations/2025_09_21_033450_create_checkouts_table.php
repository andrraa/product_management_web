<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_checkouts', function (Blueprint $table) {
            $table->id('checkout_id');
            $table->string('name');
            $table->integer('quantity');
            $table->string('payment_method');
            $table->string('type');
            $table->double('price');
            $table->double('total_price');
            $table->unsignedBigInteger('fk_user_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_checkouts');
    }
};
