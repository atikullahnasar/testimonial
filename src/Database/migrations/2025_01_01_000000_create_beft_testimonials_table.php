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
        Schema::create('beft_testimonials', function (Blueprint $table) {
            $table->id();
            $table->text('review');
            $table->string('name');
            $table->string('tag');
            $table->string('image')->nullable();
            $table->integer('featured')->default(0);
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beft_testimonials');
    }
};
