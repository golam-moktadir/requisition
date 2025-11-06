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
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->comment('Foreign Key - id:companies');
            $table->string('bank_name');
            $table->string('account_holder_name');
            $table->string('account_no');
            $table->enum('account_type', ['current', 'savings', 'fdr', 'cc'])->nullable();
            $table->string('branch_name');
            $table->string('branch_address')->nullable();
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->unique(['bank_name', 'account_no'], 'bank_account_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banks');
    }
};
