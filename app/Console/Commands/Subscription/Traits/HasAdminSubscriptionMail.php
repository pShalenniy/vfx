<?php

declare(strict_types=1);

namespace App\Console\Commands\Subscription\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

trait HasAdminSubscriptionMail
{
    protected function sendAdminSubscriptionMail(
        array $emails,
        array $users,
        string $notification,
        array $args = [],
    ): void {
        foreach ($emails as $email) {
            try {
                Mail::to($email)->send(new $notification($users, ...$args));
            } catch (Throwable $e) {
                Log::error($e->getMessage(), [
                    'command' => static::class,
                    'email' => $email,
                ]);
            }
        }
    }
}
