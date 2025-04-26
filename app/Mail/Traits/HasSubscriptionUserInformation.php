<?php

declare(strict_types=1);

namespace App\Mail\Traits;

use App\Models\JobRole;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Lang;

use function implode;

trait HasSubscriptionUserInformation
{
    use HasSubscriptionDepartments;

    protected function getSubscriptionReminderInformation(User $user): array
    {
        $subscription = $user->getRelationValue('subscription');

        return [
            'user:first_name' => $user->getAttribute('first_name'),
            'subscription:end_date' => $subscription->getAttribute('ends_at')?->format('d/m/Y'),
            'subscription:period' => $subscription->getAttribute('period'),
            'subscription:days_for_renewal' => $subscription
                ->getAttribute('ends_at')
                ->diffInDays(Carbon::now()),
        ];
    }

    protected function getSubscriptionUserInformation(User $user): array
    {
        $hasSignatory = $user->getAttribute('has_signatory')
            ? Lang::get('client/notification.subscription.user.has_signatory.yes')
            : Lang::get('client/notification.subscription.user.has_signatory.no');

        $preferredJobRoles = $user
            ->getRelationValue('preferredJobRoles')
            ?->map(static fn (JobRole $jobRole) => $jobRole->getAttribute('name'))
            ->all();

        $subscription = $user->getRelationValue('subscription') ?? new Subscription();

        return [
            'user:first_name' => $user->getAttribute('first_name'),
            'user:last_name' => $user->getAttribute('last_name'),
            'user:email' => $user->getAttribute('email'),
            'user:phone_number' => $user->getAttribute('phone_number') ?? '-',
            'user:company' => $user->getRelationValue('company')?->getAttribute('name'),
            'user:job_title' => $user->getAttribute('job_title'),
            'user:country' => $user->getRelationValue('country')?->getAttribute('name') ?? '-',
            'user:region' => $user->getRelationValue('region')?->getAttribute('name') ?? '-',
            'user:city' => $user->getRelationValue('city')?->getAttribute('name') ?? '-',
            'user:has_signatory' => $hasSignatory,
            'user:preferred_job_roles' => implode(', ', $preferredJobRoles) ?: '-',
            'subscription:seats' => $subscription->getAttribute('seats') ?? '-',
            'subscription:departments' => $this->getDepartments($subscription) ?? '-',
            'subscription:end_date' => $subscription->getAttribute('ends_at')?->format('d/m/Y') ?? '-',
            'subscription:period' => $subscription->getAttribute('period') ?? '-',
            'subscription:days_for_renewal' => $subscription
                ->getAttribute('ends_at')
                ?->diffInDays(Carbon::now()) ?? '-',
        ];
    }
}
