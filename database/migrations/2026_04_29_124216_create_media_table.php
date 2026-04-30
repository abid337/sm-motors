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
    Schema::create('media', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('item_id');
        $table->string('file_path');
        $table->enum('type', ['image', 'video'])->default('image');
        $table->boolean('is_primary')->default(false);
        $table->timestamps();

        $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
