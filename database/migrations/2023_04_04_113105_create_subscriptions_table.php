<?php

use App\Models\Subscription;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique()->nullable();
            $table->unsignedSmallInteger('status')->default(Subscription::STATUS_PENDING_DEMO);
            $table->unsignedSmallInteger('seats');
            $table->unsignedTinyInteger('period')->nullable();
            $table->boolean('contract_signed')->default(false);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->boolean('has_expired')->default(false);
            $table->unsignedTinyInteger('pause_count')->default(0);
            $table->unsignedTinyInteger('reminded_days_ago')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
