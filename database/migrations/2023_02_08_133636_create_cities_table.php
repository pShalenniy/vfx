<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tinsel_town_id')->nullable()->index();
            $table->string('name');
            $table->unsignedBigInteger('region_id')->index();
            $table->unsignedBigInteger('timezone_id')->nullable();
            $table->float('latitude', 5)->nullable();
            $table->float('longitude', 5)->nullable();
            $table->timestamps();

            $table->foreign('region_id')
                ->references('id')
                ->on('regions');

            $table->foreign('timezone_id')
                ->references('id')
                ->on('timezones')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
