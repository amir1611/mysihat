@ -1,42 +0,0 @@
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->integer('patient_id');
            $table->integer('doctor_id');
            $table->text('reason');
            $table->string('medical_conditions_record')->nullable();
            $table->text('current_medications')->nullable();
            $table->dateTime('appointment_time');
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_number');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('patient_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}