<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidate_job_role', function (Blueprint $table) {
            $table->unsignedBigInteger('candidate_id')->index();
            $table->unsignedBigInteger('job_role_id')->index();
            $table->unsignedTinyInteger('type')->nullable()->index();

            $table->unique(['candidate_id', 'job_role_id', 'type'], 'candidate_id_job_role_id_type_unique');

            $table->foreign('candidate_id')
                ->references('id')
                ->on('candidates')
                ->onDelete('cascade');

            $table->foreign('job_role_id')
                ->references('id')
                ->on('job_roles')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_job_role');
    }
};
