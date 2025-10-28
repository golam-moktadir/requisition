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
        Schema::create('account_heads', function (Blueprint $table) {
            $table->id(); 
            $table->enum('head_category', array(0,1,2,3,4,5))->default('0'); 	
            $table->string('account_head_name', 128)->unique();
            $table->bigInteger('parent_id')->default('0');
            $table->smallInteger('status')->default('0');
            $table->bigInteger('created_by')->default('0');
            $table->timestamp('created_at')->nullable(true);
            $table->timestamp('updated_at')->nullable(false)->useCurrent();
            $table->timestamp('deleted_at')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_heads');
    }
};
