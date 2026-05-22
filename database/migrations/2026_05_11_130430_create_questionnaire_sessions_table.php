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
        Schema::create('questionnaire_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique();
            $table->decimal('r_score', 5, 2)->default(0);
            $table->decimal('i_score', 5, 2)->default(0);
            $table->decimal('a_score', 5, 2)->default(0);
            $table->decimal('s_score', 5, 2)->default(0);
            $table->decimal('e_score', 5, 2)->default(0);
            $table->decimal('c_score', 5, 2)->default(0);

            $table->foreignId('questionnaire_id')->constrained('questionnaires');
            $table->enum('status', ['in_progress', 'completed'])->default('in_progress'); //untuk cek progress user dalam mengisi kuesioner
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questionnaire_sessions');
    }
};
