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
        Schema::table('services', function (Blueprint $table) {
            $table->boolean('disabled')->default(false);
            $table->unsignedBigInteger('service_category_id')->nullable()->change();
            $table->dropForeign(['service_category_id']);
            $table->foreign('service_category_id')->references('id')->on('service_categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['service_category_id']);
            $table->unsignedBigInteger('service_category_id')->nullable(false)->change();
            $table->foreign('service_category_id')->references('id')->on('service_categories')->onDelete('cascade');
        });
    }
};
