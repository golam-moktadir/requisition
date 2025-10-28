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
        Schema::create('requisition_approval', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('requisition_id')->default('0');
            $table->enum('status', ['approved', 'rejected'])->default('rejected');
            $table->string('remarks');           
            $table->unsignedBigInteger('user_id')->default('0');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisition_approval');
    }
};
