<?php

declare(strict_types=1);

namespace App\Console\Commands\Subscription;

use App\Console\Commands\Subscription\Traits\HasAdminSubscriptionMail;
use App\Console\Helpers\NullProgressBar;
use App\Mail\Client\Subscription\AdminRenewPausedMail;
use App\Mail\Client\Subscription\ClientRenewPausedMail;
use App\Models\EmailTemplateSetting;
use App\Models\Subscription;
use App\Models\User;
use App\Repositories\Contracts\Admin\EmailTemplateSettingRepositoryContract;
use App\Repositories\Factory;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

use const null;
use const true;

class RenewPaused extends Command
{
    use HasAdminSubscriptionMail;

    protected $signature = 'subscription:renew:paused {--with-progress-bar}';

    protected $description = 'Renewing paused subscriptions';

    protected Carbon $now;

    public function handle(): int
    {
        $this->now = Carbon::now();

        $query = $this->getUsersQuery();

        $progressBar = $this->option('with-progress-bar')
            ? $this->output->createProgressBar($query->count())
            : new NullProgressBar();

        $progressBar->start();

        $emails = Factory::make(EmailTemplateSettingRepositoryContract::class)->getEmails(
            EmailTemplateSetting::KEY_CLIENT_ADMIN_SUBSCRIPTION_RENEW_PAUSED,
        );

        $users = [];

        /** @var \App\Models\User $user */
        foreach ($query->lazyById(500, 'users.id') as $user) {
            try {
                $users[] = $user;

                DB::transaction(function () use ($user) {
                    /** @var \App\Models\Subscription $subscription */
                    $subscription = $user->getRelationValue('subscription');

                    $daysForRenewalSubscription = $this->now
                        ->clone()
                        ->subMonths(Subscription::PAUSE_MONTH_PERIOD)
                        ->diffInDays($subscription->getAttribute('ends_at'));

                    $subscription
                        ->forceFill([
                            'status' => Subscription::STATUS_ACTIVE,
                            'starts_at' => $this->now,
                            'ends_at' => $this->now->clone()->addDays($daysForRenewalSubscription),
                            'has_expired' => true,
                            'reminded_days_ago' => null,
                        ])
                        ->save();

                    Mail::to($user)->send(new ClientRenewPausedMail($user));
                });
            } catch (Throwable $e) {
                Log::error($e->getMessage(), [
                    'command' => static::class,
                    'id' => $user->getKey(),
                ]);
            } finally {
                $progressBar->advance();
            }
        }

        if (!empty($users)) {
            $this->sendAdminSubscriptionMail(
                $emails,
                $users,
                AdminRenewPausedMail::class,
            );
        }

        $progressBar->finish();

        return self::SUCCESS;
    }

    protected function getUsersQuery(): Builder
    {
        return User::query()
            ->select(['users.*'])
            ->join('subscriptions', 'users.id', '=', 'subscriptions.user_id')
            ->where('subscriptions.status', Subscription::STATUS_PAUSED)
            ->whereExists(function ($q) {
                $q->from('subscription_field_histories')
                    ->selectRaw('1')
                    ->whereColumn('subscription_field_histories.subscription_id', '=', 'subscriptions.id')
                    ->where('subscription_field_histories.field', 'status')
                    ->where('subscription_field_histories.new_value', Subscription::STATUS_PAUSED)
                    ->where(
                        'subscription_field_histories.created_at',
                        '>=',
                        (string) $this->now->clone()->subMonths(Subscription::PAUSE_MONTH_PERIOD)->startOfDay(),
                    )
                    ->where(
                        'subscription_field_histories.created_at',
                        '<=',
                        (string) $this->now->clone()->subMonths(Subscription::PAUSE_MONTH_PERIOD)->endOfDay(),
                    );
            })
            ->with(['subscription']);
    }
}
