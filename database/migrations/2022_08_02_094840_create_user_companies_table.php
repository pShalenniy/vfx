<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url', 500)->unique();
            $table->string('logo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_companies');
    }
};
