<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_template_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('subject')->nullable();
            $table->longText('body')->nullable();
            $table->json('emails');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_template_settings');
    }
};
