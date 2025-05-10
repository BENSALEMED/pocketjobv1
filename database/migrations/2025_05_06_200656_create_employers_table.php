<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('employers', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->string('phone');
        $table->string('domaine')->nullable();
        $table->string('type_professionnel')->nullable();
        $table->text('description')->nullable();
        $table->json('document')->nullable();
        $table->string('adresse')->nullable();
        $table->json('qualification')->nullable();
        $table->string('status')->nullable();
        $table->string('image')->nullable();
        // link to the user who created this employer
        $table->unsignedBigInteger('created_by');
        $table->timestamps();

        $table->foreign('created_by')
              ->references('id')->on('users')
              ->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employers');
    }
};
