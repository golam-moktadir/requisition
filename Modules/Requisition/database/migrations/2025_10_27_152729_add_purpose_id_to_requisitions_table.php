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
            $table->unsignedBigInteger('purpose_id')->nullable()->after('company_id');
            $table->string('others')->nullable()->after('purpose_id');
            $table->foreign('purpose_id')->references('id')->on('purposes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requisitions', function (Blueprint $table) {
            $table->dropForeign(['purpose_id']);
            $table->dropColumn('purpose_id'); 
            $table->dropColumn('others'); 
        });
    }
};
