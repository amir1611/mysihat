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
        // Schema::table('users', function (Blueprint $table) {
        //     $table->softDeletes()->after('updated_at');
        //     $table->string('gender')->nullable()->change();
        //     $table->string('date_of_birth')->nullable()->change();
        //     $table->string('phone_number')->nullable()->change();
        //     $table->string('medical_license_number')->nullable()->change();
        //     $table->string('ic_number')->nullable()->change();
        // });

        Schema::table('appointments', function (Blueprint $table) {
            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'cancelled',
                'completed',
            ])->nullable()->after('emergency_contact_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
