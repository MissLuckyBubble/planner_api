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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('address_id')->nullable();
            $table->string('name');
            $table->string('eik');
            $table->integer('rating')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->
                references('id')->on('users')->
                onDelete('cascade');
            $table->foreign('address_id')->
                references('id')->on('addresses')
                ->onDelete('set null');  // set foreign key constraint to set null on delete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
