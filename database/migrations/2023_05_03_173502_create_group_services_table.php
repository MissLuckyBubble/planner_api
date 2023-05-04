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
        Schema::create('group_services', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('service_category_id');
            $table->text('description');
            $table->decimal('price',8,2);
            $table->date('date');
            $table->time('start_time');
            $table->integer('duration_minutes');
            $table->integer('max_capacity');
            $table->timestamps();

            $table->foreign('service_category_id')->references('id')->on('service_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_services');
    }
};
