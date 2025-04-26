<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tinsel_town_id')->nullable()->index();
            $table->string('name');
            $table->unsignedBigInteger('country_id')->index();
            $table->timestamps();

            $table->foreign('country_id')
                ->references('id')
                ->on('countries');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regions');
    }
};
