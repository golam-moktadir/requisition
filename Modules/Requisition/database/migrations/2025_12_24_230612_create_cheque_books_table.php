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
      Schema::create('cheque_books', function (Blueprint $table) {
         $table->id();
         $table->unsignedBigInteger('account_id')->comment('Foreign key - id : bank_accounts');
         $table->string('book_number', 50);
         $table->string('start_cheque_no', 50);
         $table->string('end_cheque_no', 50);
         $table->timestamps();

         $table->foreign('account_id')->references('id')->on('bank_accounts');
         $table->unique(['account_id', 'book_number'], 'account_book_unique');
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('cheque_books');
   }
};
