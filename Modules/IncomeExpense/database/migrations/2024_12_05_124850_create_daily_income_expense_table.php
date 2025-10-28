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
        Schema::create('daily_income_expense', function (Blueprint $table) {
            $table->id();
            
            $table->integer('account_head_id')->default('0');
            $table->decimal('amount', 10, 2)->default('0');
            $table->string('remarks')->default('');
            $table->bigInteger('created_by')->default('0');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_income_expense');
    }
};
