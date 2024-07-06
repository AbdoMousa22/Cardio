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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('diagnoses_id')->constrained('diagnoses')->onDelete('cascade')->onUpdate('cascade');
            $table->string('name');
            $table->enum('gender',['male','female']);
            $table->string('age');
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->boolean('Diabetes');
            $table->Boolean('hypertension');
            $table->Boolean('pregnant');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
