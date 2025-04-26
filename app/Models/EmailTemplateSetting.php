<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplateSetting extends Model
{
    use HasFactory;

    public const KEY_ADMIN_SUBSCRIPTION_REQUEST_PAUSE = 'admin.subscription_request_pause';
    public const KEY_ADMIN_USER_CREATED = 'admin.user_created';
    public const KEY_ADMIN_USER_DELETED = 'admin.user_deleted';
    public const KEY_ADMIN_CANDIDATE_CREATED = 'admin.candidate_created';
    public const KEY_CLIENT_ADMIN_SUBSCRIPTION_RENEW_FIRST_PAUSED_PERIOD = 'client.admin_subscription_renew_first_paused_period';
    public const KEY_CLIENT_ADMIN_SUBSCRIPTION_REQUEST_CHANGE = 'client.admin_subscription_request_change';
    public const KEY_CLIENT_CLIENT_SUBSCRIPTION_REQUEST_CHANGE = 'client.client_subscription_request_change';
    public const KEY_CLIENT_CONTACT_US = 'client.contact_us';
    public const KEY_CLIENT_FORGOT_PASSWORD = 'client.forgot_password';
    public const KEY_CLIENT_REGISTRATION_COMPLETED = 'client.registration_completed';
    public const KEY_CLIENT_SUBSCRIPTION_REQUEST_PAUSE = 'client.subscription_request_paused';
    public const KEY_CLIENT_SUBSCRIPTION_RENEW_PAUSED = 'client.subscription_renew_paused';
    public const KEY_CLIENT_ADMIN_SUBSCRIPTION_RENEW_PAUSED = 'client.admin_subscription_renew_paused';
    public const KEY_CLIENT_RESET_PASSWORD = 'client.reset_password';
    public const KEY_CLIENT_SUBSCRIPTION_CREATED = 'client.subscription_created';
    public const KEY_CLIENT_SUBSCRIPTION_CANCELLED = 'client.subscription_cancelled';
    public const KEY_CLIENT_SUBSCRIPTION_RENEW_FIRST_PAUSED_PERIOD = 'client.subscription_renew_first_paused_period';
    public const KEY_CLIENT_SUBSCRIPTION_PROCESS_REQUEST_PAUSE = 'client.subscription_process_request_pause';
    public const KEY_CLIENT_SUBSCRIPTION_RENEW_LAST_PAUSED_PERIOD = 'client.subscription_renew_last_paused_period';
    public const KEY_CLIENT_ADMIN_SUBSCRIPTION_RENEW_LAST_PAUSED_PERIOD = 'client.admin_subscription_renew_last_paused_period';
    public const KEY_CLIENT_VERIFY_EMAIL = 'client.verify_email';

    protected $table = 'email_template_settings';

    protected $fillable = [
        'key',
        'subject',
        'body',
        'emails',
    ];

    protected $casts = [
        'key' => 'string',
        'subject' => 'string',
        'body' => 'string',
        'emails' => 'json',
    ];
}
