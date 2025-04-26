<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidate_imdb_filmography_episodes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('filmography_id')->index('filmography_id');
            $table->string('title');
            $table->string('year')->nullable();
            $table->string('details')->nullable();
            $table->string('imdb_id');
            $table->timestamps();

            $table->unique(['filmography_id', 'imdb_id'], 'filmography_id_imdb_id_unique');

            $table->foreign('filmography_id')
                ->references('id')
                ->on('candidate_imdb_filmographies')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_imdb_filmography_episodes');
    }
};
