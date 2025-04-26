<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidate_preferred_work_environment', function (Blueprint $table) {
            $table->unsignedBigInteger('candidate_id')->index('candidate_preferred_work_environment_candidate_id_index');
            $table->unsignedBigInteger('preferred_work_environment_id')->index('preferred_work_environment_id_index');

            $table->unique(['candidate_id', 'preferred_work_environment_id'], 'preferred_work_environment_id_unique');

            $table->foreign(['candidate_id'], 'candidate_id_foreign')
                ->references('id')
                ->on('candidates')
                ->onDelete('cascade');

            $table->foreign(['preferred_work_environment_id'], 'preferred_work_environment_id_foreign')
                ->references('id')
                ->on('preferred_work_environments')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_preferred_work_environment');
    }
};
