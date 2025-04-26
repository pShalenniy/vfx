<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidate_linkedin_experience_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('experience_id')->index();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->string('dates')->nullable();
            $table->string('employment')->nullable();
            $table->timestamps();

            $table->foreign('experience_id')
                ->references('id')
                ->on('candidate_linkedin_experiences')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_linkedin_experience_details');
    }
};
