<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidate_nationality', function (Blueprint $table) {
            $table->unsignedBigInteger('candidate_id')->index();
            $table->unsignedBigInteger('country_id')->index();

            $table->unique(['candidate_id', 'country_id']);

            $table->foreign('candidate_id')
                ->references('id')
                ->on('candidates')
                ->onDelete('cascade');

            $table->foreign('country_id')
                ->references('id')
                ->on('countries')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_nationality');
    }
};
