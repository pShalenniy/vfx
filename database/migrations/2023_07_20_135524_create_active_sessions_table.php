<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('active_sessions', function (Blueprint $table) {
            $table->ulid('id');
            $table->unsignedBigInteger('token_id')->index();
            $table->unsignedBigInteger('model_id')->index();
            $table->string('model_type')->index();
            $table->string('browser')->nullable();
            $table->string('os')->nullable();
            $table->ipAddress('ip')->nullable();
            $table->dateTime('last_activated_at')->nullable();
            $table->timestamps();

            $table->foreign('token_id')
                ->references('id')
                ->on('personal_access_tokens')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('active_sessions');
    }
};
