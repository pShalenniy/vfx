<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_preferred_job_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('job_role_id')->index();
            $table->unsignedBigInteger('user_id')->index();

            $table->unique(['job_role_id', 'user_id']);

            $table->foreign('job_role_id')
                ->references('id')
                ->on('job_roles')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_preferred_job_roles');
    }
};
