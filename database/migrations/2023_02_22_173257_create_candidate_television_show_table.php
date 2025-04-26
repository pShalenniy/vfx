<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidate_television_show', function (Blueprint $table) {
            $table->unsignedBigInteger('candidate_id')->index();
            $table->unsignedBigInteger('television_show_id')->index();
            $table->unsignedBigInteger('skill_id')->nullable()->index();

            $table->unique(['candidate_id', 'television_show_id']);

            $table->foreign('candidate_id')
                ->references('id')
                ->on('candidates')
                ->onDelete('cascade');

            $table->foreign('television_show_id')
                ->references('id')
                ->on('television_shows')
                ->onDelete('cascade');

            $table->foreign('skill_id')
                ->references('id')
                ->on('skills')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_television_show');
    }
};
