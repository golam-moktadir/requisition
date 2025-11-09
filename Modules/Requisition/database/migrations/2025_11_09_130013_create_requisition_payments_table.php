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
        Schema::create('requisition_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requisition_id')->comment('Foreign Key - id: requisitions');
            $table->tinyInteger('payment_type')->comment('1 = Cheque, 2 = Cash');
            $table->unsignedBigInteger('cheque_id')->nullable()->comment('Foreign Key - id: cheques');
            $table->decimal('cash_amount', 10, 2)->nullable();
            $table->text('cash_description')->nullable();
            $table->json('files')->nullable();
            $table->timestamps();
            $table->foreign('requisition_id')->references('id')->on('requisitions');
            $table->foreign('cheque_id')->references('id')->on('cheques');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisition_payments');
    }
};
