<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('requisitions', function (Blueprint $table) {
            $table->enum('transaction_mode', ['cash', 'bank', 'due'])->nullable()->change();
            $table->string('bank_check_info')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('requisitions', function (Blueprint $table) {
            $table->enum('transaction_mode', ['cash', 'bank', 'due'])->default('due')->change();
            $table->string('bank_check_info')->default('')->change();
        });
    }
};
