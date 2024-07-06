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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();
            $table->boolean('not_for_diabetes')->default(false);
            $table->boolean('not_for_hypertension')->default(false);
            $table->boolean('not_for_pregnant')->default(false);
            $table->string('brand_name')->nullable();
            $table->string('active_ingredient')->nullable();
            $table->text('dosage')->nullable();//الجرعة
            $table->string('dosage_form')->nullable();// شكل الدواء
            $table->string('administration_route')->nullable();//طريقة التناول
            $table->text('side_effects')->nullable();
            $table->string('alternatives')->nullable();  //البدائل
            $table->string('manufacturer')->nullable();
            $table->text('interaction')->nullable();
            $table->text('contraindications')->nullable();//موانع الاستعمال
            $table->text('instructions')->nullable();//زي طريقة عمله ومكان تأثيرة بالجسم وطريقة التخزين
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medcinies');
    }
};
