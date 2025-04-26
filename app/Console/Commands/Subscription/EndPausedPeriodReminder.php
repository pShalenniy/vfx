<?php

declare(strict_types=1);

namespace App\Console\Commands\Subscription;

use App\Console\Commands\Subscription\Traits\HasAdminSubscriptionMail;
use App\Mail\Client\Subscription\AdminRenewFirstPausedPeriodMail;
use App\Mail\Client\Subscription\AdminRenewLastPausedPeriod;
use App\Mail\Client\Subscription\ClientRenewFirstPausedPeriodMail;
use App\Mail\Client\Subscription\ClientRenewLastPausedPeriod;
use App\Models\EmailTemplateSetting;
use App\Models\Subscription;
use App\Models\User;
use App\Repositories\Contracts\Admin\EmailTemplateSettingRepositoryContract;
use App\Repositories\Factory;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\LazyCollection;
use McMatters\Helpers\Helpers\ClassHelper;
use Throwable;

use function rsort;

use const null;

class EndPausedPeriodReminder extends Command
{
    use HasAdminSubscriptionMail;

    protected $signature = 'subscription:end-paused-period:reminder {--with-progress-bar}';

    protected $description = 'Reminding user about expiring subscription';

    protected Carbon $now;

    public function handle(): int
    {
        $this->now = Carbon::now();

        $pauseCountMap = [
            1 => 'sendRenewFirstPausedPeriodMails',
            2 => 'sendRenewLastPausedPeriodMails',
        ];

        $periods = $this->getPeriods();

        foreach ($pauseCountMap as $pauseCount => $method) {
            $this->processUsers($periods, $pauseCount, $method);
        }

        return self::SUCCESS;
    }

    protected function processUsers(
        array $periods,
        int $pauseCount,
        string $method,
    ): void {
        $previousPeriod = null;

        foreach ($periods as $period) {
            $this->{$method}(
                $this->getUsersByPeriod(
                    $period,
                    $pauseCount,
                    $previousPeriod,
                ),
                $period,
            );

            $previousPeriod = $period;
        }
    }

    protected function getUsersByPeriod(
        int $currentPeriod,
        int $pauseCount,
        ?int $previousPeriod = null,
    ): LazyCollection {
        $subscriptionPauseStartDate = $this->now
            ->clone()
            ->addDays($currentPeriod)
            ->subMonths(Subscription::PAUSE_MONTH_PERIOD);

        return User::query()
            ->select(['users.*'])
            ->join('subscriptions', 'users.id', '=', 'subscriptions.user_id')
            ->where('subscriptions.status', Subscription::STATUS_PAUSED)
            ->when(null !== $previousPeriod, static function ($q) use ($previousPeriod) {
                $q->whereNested(static function ($q) use ($previousPeriod) {
                    $q
                        ->where('subscriptions.reminded_days_ago', '>', $previousPeriod)
                        ->orWhereNull('subscriptions.reminded_days_ago');
                });
            })
            ->where('subscriptions.pause_count', $pauseCount)
            ->whereExists(static function ($q) use ($subscriptionPauseStartDate) {
                $q->from('subscription_field_histories')
                    ->selectRaw('1')
                    ->whereColumn('subscription_field_histories.subscription_id', '=', 'subscriptions.id')
                    ->where(
                        'subscription_field_histories.created_at',
                        '>=',
                        (string) $subscriptionPauseStartDate->clone()->startOfDay(),
                    )
                    ->where(
                        'subscription_field_histories.created_at',
                        '<=',
                        (string) $subscriptionPauseStartDate->clone()->endOfDay(),
                    )
                    ->where('subscription_field_histories.field', 'status');
            })
            ->with(['subscription'])
            ->lazyById(500, 'users.id');
    }

    protected function sendRenewFirstPausedPeriodMails(
        LazyCollection $users,
        int $remindPeriod,
    ): void {
        $emails = Factory::make(EmailTemplateSettingRepositoryContract::class)->getEmails(
            EmailTemplateSetting::KEY_CLIENT_ADMIN_SUBSCRIPTION_RENEW_FIRST_PAUSED_PERIOD,
        );

        $renewFirstPausedPeriodUsers = [];

        foreach ($users as $user) {
            try {
                $renewFirstPausedPeriodUsers[] = $user;

                DB::transaction(function () use ($user, $remindPeriod) {
                    $this->setSubscriptionRemindedDaysAgoValue($user, $remindPeriod);

                    Mail::to($user)->send(new ClientRenewFirstPausedPeriodMail($user));
                });
            } catch (Throwable $e) {
                Log::error($e->getMessage(), [
                    'command' => static::class,
                    'id' => $user->getKey(),
                ]);
            }
        }

        if (!empty($renewFirstPausedPeriodUsers)) {
            $this->sendAdminSubscriptionMail(
                $emails,
                $renewFirstPausedPeriodUsers,
                AdminRenewFirstPausedPeriodMail::class,
                [$remindPeriod],
            );
        }
    }

    protected function sendRenewLastPausedPeriodMails(
        LazyCollection $users,
        int $remindPeriod,
    ): void {
        $emails = Factory::make(EmailTemplateSettingRepositoryContract::class)->getEmails(
            EmailTemplateSetting::KEY_CLIENT_ADMIN_SUBSCRIPTION_RENEW_PAUSED,
        );

        $renewLastPausedPeriodUsers = [];

        foreach ($users as $user) {
            try {
                $renewLastPausedPeriodUsers[] = $user;

                DB::transaction(function () use ($user, $remindPeriod) {
                    $this->setSubscriptionRemindedDaysAgoValue($user, $remindPeriod);

                    Mail::to($user)->send(new ClientRenewLastPausedPeriod($user));
                });
            } catch (Throwable $e) {
                Log::error($e->getMessage(), [
                    'command' => static::class,
                    'id' => $user->getKey(),
                ]);
            }
        }

        if (!empty($renewLastPausedPeriodUsers)) {
            $this->sendAdminSubscriptionMail(
                $emails,
                $renewLastPausedPeriodUsers,
                AdminRenewLastPausedPeriod::class,
                [$remindPeriod],
            );
        }
    }

    protected function setSubscriptionRemindedDaysAgoValue(
        User $user,
        int $remindPeriod,
    ): void {
        $user->getRelationValue('subscription')
            ->forceFill(['reminded_days_ago' => $remindPeriod])
            ->save();
    }

    protected function getPeriods(): array
    {
        $periods = ClassHelper::getConstantsStartWith(Subscription::class, 'REMIND_PERIOD_');

        rsort($periods);

        return $periods;
    }
}
