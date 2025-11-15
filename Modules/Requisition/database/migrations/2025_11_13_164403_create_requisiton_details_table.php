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
        Schema::create('requisition_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requisition_id')->comment('Foreign Key - id : requisitions');
            $table->string('description')->nullable();
            $table->decimal('amount', 10, 2);
            $table->timestamps();
            $table->foreign('requisition_id')->references('id')->on('requisitions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisition_details');
    }
};
