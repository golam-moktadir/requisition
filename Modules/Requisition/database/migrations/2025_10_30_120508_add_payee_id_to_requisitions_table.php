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
        Schema::table('requisitions', function (Blueprint $table) {
            $table->unsignedBigInteger('payee_id')->nullable()->after('purpose_id')->comment('Foreign Key - payee_id:payees');
            $table->foreign('payee_id')->references('id')->on('payees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requisitions', function (Blueprint $table) {
            $table->dropForeign(['payee_id']);
            $table->dropColumn('payee_id');  
        });
    }
};
