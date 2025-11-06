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
        Schema::create('cheque_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cheque_id')->comment('Foreign Key : id - cheques');
            $table->string('file_name');
            $table->timestamps();
            $table->foreign('cheque_id')->references('id')->on('cheques');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cheque_files');
    }
};
