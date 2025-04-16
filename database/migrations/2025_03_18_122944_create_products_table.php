<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id()->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('maker_id');
            $table->foreign('maker_id')->references('id')->on('makers')->onDelete('cascade');
            $table->decimal('price', 10, 2);
            $table->string('category');
            $table->date('ordered_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
