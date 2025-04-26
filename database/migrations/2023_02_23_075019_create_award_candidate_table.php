<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('award_candidate', function (Blueprint $table) {
            $table->unsignedBigInteger('award_id')->index();
            $table->unsignedBigInteger('candidate_id')->index();
            $table->unsignedBigInteger('television_show_id')->nullable()->index();
            $table->unsignedtinyInteger('result')->nullable();

            $table->unique(['award_id', 'candidate_id']);

            $table->foreign('award_id')
                ->references('id')
                ->on('awards')
                ->onDelete('cascade');

            $table->foreign('candidate_id')
                ->references('id')
                ->on('candidates')
                ->onDelete('cascade');

            $table->foreign('television_show_id')
                ->references('id')
                ->on('television_shows')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('award_candidate');
    }
};
