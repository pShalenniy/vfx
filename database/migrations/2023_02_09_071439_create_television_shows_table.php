<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('television_shows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tinsel_town_id')->nullable()->index();
            $table->string('name', 1000);
            $table->year('start_year')->nullable();
            $table->year('end_year')->nullable();
            $table->string('season')->nullable();
            $table->string('imdb_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('television_shows');
    }
};
