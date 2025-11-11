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
        Schema::table('requisition_approval', function (Blueprint $table) {
            $table->enum('status', ['approved', 'rejected', 'pending', 'returned', 'issued'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requisition_approval', function (Blueprint $table) {
            $table->enum('status', ['approved', 'rejected', 'pending', 'returned'])->nullable()->change();
        });
    }
};
