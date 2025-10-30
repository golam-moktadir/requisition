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
            $table->unsignedBigInteger('requisition_id')->comment('Foreign Key - id: requisitions')->change();
            $table->enum('status', ['approved', 'rejected', 'pending'])->nullable()->change();
            $table->foreign('requisition_id')->references('id')->on('requisitions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requisition_approval', function (Blueprint $table) {
            $table->unsignedBigInteger('requisition_id')->default('0')->change();
            $table->enum('status', ['approved', 'rejected'])->default('rejected')->change();
            $table->dropForeign(['requisition_id']);
        });
    }
};
