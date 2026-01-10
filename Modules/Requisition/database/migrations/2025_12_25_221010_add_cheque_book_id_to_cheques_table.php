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
        Schema::table('cheques', function (Blueprint $table) {
            $table->unsignedBigInteger('cheque_book_id')->comment('Foreign key - id: cheque_books')->after('id');
            $table->foreign('cheque_book_id')->references('id')->on('cheque_books');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cheques', function (Blueprint $table) {
            $table->dropForeign(['cheque_book_id']);
            $table->dropColumn('cheque_book_id');
        });
    }
};
