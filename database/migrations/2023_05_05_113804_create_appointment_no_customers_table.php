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
        Schema::create('appointment_no_customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->decimal('total_price',8,2);
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('duration');
            $table->string('status');
            $table->string('name')->nullable();
            $table->string('phoneNumber')->nullable();
            $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_no_customers');
    }
};
