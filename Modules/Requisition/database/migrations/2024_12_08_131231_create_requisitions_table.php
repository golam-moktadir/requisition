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
        Schema::create('requisitions', function (Blueprint $table) {

            $table->id();
            $table->string('title');            
            $table->text('description');
            $table->enum('requested_to', ['managing_director', 'ceo', 'manager', 'accountant'])->default('accountant');
            $table->decimal('amount' , 11, 3)->default('0');
            $table->enum('transaction_mode', ['cash', 'bank', 'due'])->default('due');
            $table->string('bank_check_info')->default('');      

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');   
            $table->unsignedBigInteger('created_by')->default('0');   
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisitions');
    }
};
