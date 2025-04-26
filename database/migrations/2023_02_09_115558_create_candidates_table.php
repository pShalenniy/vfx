<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tinsel_town_id')->nullable()->index();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->unsignedBigInteger('city_id')->nullable()->index();
            $table->unsignedBigInteger('region_id')->nullable()->index();
            $table->unsignedBigInteger('country_id')->nullable()->index();
            $table->unsignedBigInteger('timezone_id')->nullable()->index();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedTinyInteger('budget_of_biggest_show')->nullable();
            $table->unsignedBigInteger('phone_number')->nullable();
            $table->json('portfolio_url')->nullable();
            $table->json('shortfilm_url')->nullable();
            $table->unsignedFloat('gross_annual_salary', 10, 2)->nullable();
            $table->unsignedFloat('week_rate')->nullable();
            $table->unsignedFloat('day_rate')->nullable();
            $table->text('would_like_work_on')->nullable();
            $table->dateTime('commercial_experience')->nullable();
            $table->boolean('travel_availability')->nullable()->default(false);
            $table->unsignedTinyInteger('salary_rate_currency')->nullable();
            $table->text('vfx_notes')->nullable();
            $table->string('picture')->nullable();
            $table->string('imdb_link', 2000)->nullable();
            $table->string('linkedin_link', 2000)->nullable();
            $table->string('instagram_link', 2000)->nullable();
            $table->string('twitter_link', 2000)->nullable();
            $table->text('current_work')->nullable();
            $table->text('previous_work')->nullable();
            $table->text('professional_interest')->nullable();
            $table->dateTime('next_availability')->nullable();
            $table->unsignedTinyInteger('source')->index();
            $table->string('slug')->unique();
            $table->json('skill_circles')->nullable();
            $table->timestamps();

            $table->foreign('city_id')
                ->references('id')
                ->on('cities')
                ->onDelete('set null');

            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('set null');

            $table->foreign('country_id')
                ->references('id')
                ->on('countries')
                ->onDelete('set null');

            $table->foreign('region_id')
                ->references('id')
                ->on('regions')
                ->onDelete('set null');

            $table->foreign('timezone_id')
                ->references('id')
                ->on('timezones')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
