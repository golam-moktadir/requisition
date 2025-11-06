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
        Schema::create('cheques', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bank_id')->comment('Foreign Key - id : banks');
            $table->bigInteger('requisition_id')->default(0)->comment('Foreign Key - id : requisitions');
            $table->string('cheque_no');
            $table->decimal('amount', 12, 2)->default(0);
            $table->integer('status')->default(1)->comment('1 - Active, 2 - Inactive, 3 - Used');
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->foreign('bank_id')->references('id')->on('banks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cheques');
    }
};
