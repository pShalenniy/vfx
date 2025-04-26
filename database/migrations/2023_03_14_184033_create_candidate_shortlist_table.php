<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidate_shortlist', function (Blueprint $table) {
            $table->unsignedBigInteger('candidate_id')->index();
            $table->unsignedBigInteger('shortlist_id')->index();

            $table->unique(['candidate_id', 'shortlist_id']);

            $table->foreign('candidate_id')
                ->references('id')
                ->on('candidates')
                ->onDelete('cascade');

            $table->foreign('shortlist_id')
                ->references('id')
                ->on('shortlists')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_shortlist');
    }
};
