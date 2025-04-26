<?php

declare(strict_types=1);

namespace App\Http\Resources\Admin;

use App\Http\Resources\Traits\HasUserCompany;
use App\Models\Department;
use App\Models\JobRole;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Lang;

use function implode;

use const false;
use const null;

class UserResource extends JsonResource
{
    use HasUserCompany;

    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getKey(),
            'first_name' => $this->resource->getAttribute('first_name'),
            'last_name' => $this->resource->getAttribute('last_name'),
            'email' => $this->resource->getAttribute('email'),
            'company' => $this->getCompanyAttribute(
                $this->resource->getRelationValue('company')?->only(['id', 'name', 'url', 'logo']) ?? [],
            ),
            'city' => $this->resource->getRelationValue('city')?->only(['id', 'name', 'region_id']),
            'region' => $this->resource->getRelationValue('region')?->only(['id', 'name', 'country_id']),
            'country' => $this->resource->getRelationValue('country')?->only(['id', 'name']),
            'job_title' => $this->resource->getAttribute('job_title'),
            'has_signatory' => $this->resource->getAttribute('has_signatory'),
            'preferred_job_roles' => $this->resource
                ->getRelationValue('preferredJobRoles')
                ?->map(static fn (JobRole $jobRole) => $jobRole->only(['id', 'name'])),
            'phone_code' => $this->resource->getAttribute('phone_code'),
            'phone_number' => $this->resource->getAttribute('phone_number'),
            'role_id' => $this->resource->getRelationValue('role')?->only(['id', 'name']),
            'subscription' => $this->getSubscription(
                $this->resource->getRelationValue('subscription'),
            ),
        ];
    }

    protected function getFieldHistories(Subscription $subscription): array
    {
        $attributes = [];

        foreach ($subscription->getRelationValue('fieldHistories') as $fieldHistory) {
            $field = $fieldHistory->getAttribute('field');

            $attributes[$field][] = [
                'id' => $fieldHistory->getKey(),
                'subscription_id' => $fieldHistory->getAttribute('subscription_id'),
                'new_value' => $this->getValueAttribute(
                    $fieldHistory->getAttribute('new_value'),
                    $field,
                ),
                'previous_value' => $this->getValueAttribute(
                    $fieldHistory->getAttribute('previous_value'),
                    $field,
                ),
                'created_at' => $fieldHistory->getAttribute('created_at')?->format('d/m/Y'),
                'raw' => [
                    'previous' => $fieldHistory->getAttribute('previous_value'),
                    'new' => $fieldHistory->getAttribute('new_value'),
                ],
            ];
        }

        return $attributes;
    }

    protected function getDepartments(): array
    {
        static $departments;

        if (null === $departments) {
            $departments = Department::query()->pluck('name', 'id')->all();
        }

        return $departments;
    }

    protected function getSubscription(?Subscription $subscription): array
    {
        if (null === $subscription) {
            return [
                'status' => null,
                'period' => null,
                'departments' => [],
                'seats' => null,
                'contract_signed' => false,
            ];
        }

        return [
            'id' => $subscription->getKey(),
            'status' => $subscription->getAttribute('status'),
            'period' => $subscription->getAttribute('period'),
            'departments' => $subscription
                ->getRelationValue('departments')
                ?->map(static fn (Department $department) => $department->only(['id', 'name'])),
            'contract_signed' => $subscription->getAttribute('contract_signed'),
            'seats' => $subscription->getAttribute('seats'),
            'starts_at' => $subscription->getAttribute('starts_at')?->format('d/m/Y'),
            'ends_at' => $subscription->getAttribute('ends_at')?->format('d/m/Y'),
            'is_expiring' => $subscription->getIsExpiringAttribute(),
            'days_for_expiring' => $subscription->getAttribute('ends_at')?->diffInDays(Carbon::now()),
            'has_expired' => $subscription->getAttribute('has_expired'),
            'pause_count' => $this->resource->getAttribute('pause_count'),
            'field_histories' => $this->getFieldHistories($subscription),
        ];
    }

    protected function getValueAttribute(
        mixed $value,
        string $field,
    ): mixed {
        if ('status' === $field) {
            return null !== $value
                ? Lang::get("admin/subscription.constants.status.{$value}")
                : null;
        }

        if ('period' === $field) {
            return null !== $value
                ? Lang::get("admin/subscription.constants.period.{$value}")
                : null;
        }

        if ('starts_at' === $field || 'ends_at' === $field) {
            return $value
                ? Carbon::parse($value)?->format('d/m/Y')
                : '';
        }

        if ('contract_signed' === $field) {
            return $value
                ? Lang::get('admin/subscription.contract_signed.yes')
                : Lang::get('admin/subscription.contract_signed.no');
        }

        if ('departments' === $field) {
            if (empty($value)) {
                return '';
            }

            $departments = [];
            $departmentValues = $this->getDepartments();

            foreach ($value as $departmentId) {
                if ($department = $departmentValues[$departmentId] ?? null) {
                    $departments[] = $department;
                }
            }

            return implode(', ', $departments);
        }

        return $value;
    }
}
