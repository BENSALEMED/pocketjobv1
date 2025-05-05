<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // French-named candidate fields
            $table->string('diplome')->nullable()->after('email');
            $table->string('telephone')->nullable()->after('diplome');
            $table->enum('status', ['pending', 'active', 'banned'])
                  ->default('pending')
                  ->after('telephone');
            // Inscription date (optional, separate from created_at)
            $table->timestamp('date_inscription')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['diplome', 'telephone', 'status', 'date_inscription']);
        });
    }
};
