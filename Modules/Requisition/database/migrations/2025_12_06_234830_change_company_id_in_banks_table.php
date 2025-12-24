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
        Schema::table('banks', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->nullable()->comment('Foreign Key - id:companies')->change();
            $table->string('account_holder_name')->nullable()->change();
            $table->string('account_no')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banks', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->comment('Foreign Key - id:companies')->change();
            $table->string('account_holder_name')->change();
            $table->string('account_no')->change();
        });
    }
};
