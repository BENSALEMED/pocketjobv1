<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 1) Rename existing English columns if they exist
            if (Schema::hasColumn('users', 'diploma')) {
                $table->renameColumn('diploma', 'diplome');
            }
            if (Schema::hasColumn('users', 'phone')) {
                $table->renameColumn('phone', 'telephone');
            }

            // 2) If your date_inscription does not yet exist, add it
            if (! Schema::hasColumn('users', 'date_inscription')) {
                $table->timestamp('date_inscription')
                      ->nullable()
                      ->after('status');
            }

            // 3) (Optional) Ensure status is in the right enum set
            //    You can leave this out if 'status' already exists and is correct
            if (! Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['pending','active','banned'])
                      ->default('pending')
                      ->after('telephone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Reverse the renames
            if (Schema::hasColumn('users', 'diplome')) {
                $table->renameColumn('diplome', 'diploma');
            }
            if (Schema::hasColumn('users', 'telephone')) {
                $table->renameColumn('telephone', 'phone');
            }
            // Drop the added column
            if (Schema::hasColumn('users', 'date_inscription')) {
                $table->dropColumn('date_inscription');
            }
            // (Optional) drop status if you added it here
            // if (Schema::hasColumn('users', 'status')) {
            //     $table->dropColumn('status');
            // }
        });
    }
};
