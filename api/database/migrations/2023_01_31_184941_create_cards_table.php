<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->string('id', 16)->primary();
            $table->foreignId('_fk_employee_id')->constrained('employees');
            $table->float('credit');
            $table->dateTime('last_scanned');
            $table->boolean('is_logged_in');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
