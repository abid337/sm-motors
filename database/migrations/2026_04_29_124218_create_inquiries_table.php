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
    Schema::create('inquiries', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('item_id')->nullable();
        $table->string('name');
        $table->string('phone');
        $table->string('email')->nullable();
        $table->text('message')->nullable();
        $table->timestamps();

        $table->foreign('item_id')->references('id')->on('items')->onDelete('set null');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
