<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('department_job_role', function (Blueprint $table) {
            $table->unsignedBigInteger('department_id')->index();
            $table->unsignedBigInteger('job_role_id')->index();

            $table->unique(['department_id', 'job_role_id']);

            $table->foreign('department_id')
                ->references('id')
                ->on('departments')
                ->onDelete('cascade');

            $table->foreign('job_role_id')
                ->references('id')
                ->on('job_roles')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('department_job_role');
    }
};
