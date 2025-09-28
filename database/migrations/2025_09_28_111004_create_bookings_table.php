<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_bookings', function (Blueprint $table) {
            $table->id('booking_id');
            $table->string('customer_name');
            $table->string('customer_phone')->nullable();
            $table->string('payment_method');
            $table->string('package_name');
            $table->double('package_price');
            $table->integer('package_quantity');
            $table->double('total');
            $table->unsignedBigInteger('by_user_id');
            $table->string('by_user_name');
            $table->string('notes')->nullable();
            $table->boolean('is_paid')->default(0);
            $table->boolean('is_completed')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_bookings');
    }
};
