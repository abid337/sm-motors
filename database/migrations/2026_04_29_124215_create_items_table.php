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
    Schema::create('items', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('slug')->unique();
        $table->text('description')->nullable();
        $table->decimal('price', 12, 2);
        $table->string('thumbnail')->nullable();
        $table->enum('status', ['draft', 'published'])->default('published');
        $table->enum('condition', ['new', 'used'])->default('used');
        $table->boolean('featured')->default(false);
        $table->unsignedBigInteger('category_id');
        $table->unsignedBigInteger('city_id')->nullable();
        $table->unsignedBigInteger('user_id')->nullable();
        $table->timestamps();

        $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        $table->foreign('city_id')->references('id')->on('cities')->onDelete('set null');
        $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
