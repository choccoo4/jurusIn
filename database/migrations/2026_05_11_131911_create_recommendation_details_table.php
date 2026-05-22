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
        Schema::create('recommendation_details', function (Blueprint $table) {
            $table->id();
            $table->float('similarity_score');
            $table->float('riasec_match_score')->nullable();
            $table->integer('rank');
            $table->text('reasoning')->nullable();

            $table->foreignId('recommendation_id')->constrained('recommendations')->cascadeOnDelete();
            $table->foreignId('major_id')->constrained('majors')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommendation_details');
    }
};
