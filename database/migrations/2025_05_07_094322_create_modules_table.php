<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModulesTable extends Migration
{
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();

            // Store the uploaded diploma PDF filename/path
            $table->string('diploma')->nullable();

            $table->string('name');
            $table->string('contract_duration');
            $table->integer('salary');
            $table->text('skills');
            $table->string('work_location');
            $table->text('description')->nullable();
            $table->string('status')->default('PENDING');

            // Who created this module
            $table->foreignId('created_by')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('modules');
    }
}
