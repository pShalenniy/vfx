<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidate_imdb_filmographies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidate_id')->index();
            $table->string('role_type');
            $table->string('title');
            $table->string('role')->nullable();
            $table->string('year')->nullable();
            $table->string('imdb_id');
            $table->string('poster_url', 2000)->nullable();
            $table->timestamps();

            $table->foreign('candidate_id')
                ->references('id')
                ->on('candidates')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_imdb_filmographies');
    }
};
