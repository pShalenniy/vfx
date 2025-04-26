<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidate_preferred_location', function (Blueprint $table) {
            $table->unsignedBigInteger('candidate_id')->index('candidate_index');
            $table->unsignedBigInteger('preferred_location_id')->index('preferred_location_index');

            $table->unique(['candidate_id', 'preferred_location_id'], 'candidate_id_preferred_location_id_unique');

            $table->foreign('candidate_id')
                ->references('id')
                ->on('candidates')
                ->onDelete('cascade');

            $table->foreign('preferred_location_id')
                ->references('id')
                ->on('preferred_locations')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_preferred_location');
    }
};
