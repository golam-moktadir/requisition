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
        Schema::create('requisition_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requisition_id')->comment('Foreign Key : id - requisitions');
            $table->string('file_name');
            $table->timestamps();
            $table->foreign('requisition_id')->references('id')->on('requisitions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisition_files');
    }
};
