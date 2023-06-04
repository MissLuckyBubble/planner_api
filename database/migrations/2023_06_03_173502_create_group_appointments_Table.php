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
        Schema::create('group_appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('service_category_id');
            $table->string('title');
            $table->text('description');
            $table->decimal('price',8,2);
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('duration');
            $table->integer('max_capacity');
            $table->integer('count_ppl')->default(0);
            $table->string('status');
            $table->timestamps();
            $table->integer('reminders')->default(0);
            $table->foreign('service_category_id')->references('id')->on('businesses')->onDelete('cascade');
            $table->foreign('service_category_id')->references('id')->on('service_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_appointments');
    }
};
