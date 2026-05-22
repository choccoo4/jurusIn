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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->text('question_text');
            $table->integer('order_number');

            $table->foreignId('questionnaire_id')->constrained('questionnaires')->onDelete('cascade');
            $table->enum('riasec_category', ['R', 'I', 'A', 'S', 'E', 'C']);
            $table->float('riasec_weight')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
