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
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bank_id')->comment('Foreign Key - id : banks');
            $table->string('account_holder_name');
            $table->string('account_number');
            $table->string('branch_name')->nullable();
            $table->integer('account_type')->nullable();
            $table->timestamps();
            $table->foreign('bank_id')->references('id')->on('banks');
            $table->unique(['bank_id', 'account_number'], 'bank_account_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
    }
};
