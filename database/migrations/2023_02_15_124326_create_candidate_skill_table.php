<?php

declare(strict_types=1);

use App\Models\Pivot\CandidateSkill;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidate_skill', function (Blueprint $table) {
            $table->unsignedBigInteger('candidate_id')->index();
            $table->unsignedBigInteger('skill_id')->index();
            $table->unsignedTinyInteger('level')->nullable();
            $table->unsignedTinyInteger('type')->nullable()->index();

            $table->unique(['candidate_id', 'skill_id', 'level', 'type'], 'candidate_id_skill_id_level_type_unique');

            $table->foreign('candidate_id')
                ->references('id')
                ->on('candidates')
                ->onDelete('cascade');

            $table->foreign('skill_id')
                ->references('id')
                ->on('skills')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_skill');
    }
};
