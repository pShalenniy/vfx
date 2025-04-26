<?php

declare(strict_types=1);

namespace App\Console\Commands\PersonalAccessToken;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;
use Throwable;

class Clear extends Command
{
    protected $signature = 'personal-access-token:clear';

    protected $description = 'Clear tokens that were last used an hour ago and don\'t have an active session';

    public function handle(): int
    {
        $iterator = PersonalAccessToken::query()
            ->select(['personal_access_tokens.*'])
            ->leftJoin('active_sessions', 'active_sessions.token_id', '=', 'personal_access_tokens.id')
            ->whereNull('active_sessions.id')
            ->where('last_used_at', '<=', (string) Carbon::now()->subHour())
            ->lazyById();

        foreach ($iterator as $token) {
            try {
                $token->delete();
            } catch (Throwable $e) {
                Log::error($e->getMessage(), [
                    'command' => static::class,
                    'id' => $token->getKey(),
                ]);
            }
        }

        return self::SUCCESS;
    }
}
