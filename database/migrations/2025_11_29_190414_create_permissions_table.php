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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->comment('Foreign key - id: users');
            $table->unsignedSmallInteger('page_id')->comment('Foreign key - page_id: page');
            $table->unsignedTinyInteger('permission_view')->default(0);
            $table->unsignedTinyInteger('permission_insert')->default(0);
            $table->unsignedTinyInteger('permission_update')->default(0);
            $table->unsignedTinyInteger('permission_delete')->default(0);
            $table->unsignedInteger('permission_saved_by')->default(0);
            $table->unsignedTinyInteger('permission_save_status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
