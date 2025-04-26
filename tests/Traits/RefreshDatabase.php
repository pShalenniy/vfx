<?php

declare(strict_types=1);

namespace Tests\Traits;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase as BaseRefreshDatabase;

use const null;

trait RefreshDatabase
{
    use BaseRefreshDatabase;

    protected function refreshInMemoryDatabase(): void
    {
        $this->artisan('test:migrate', $this->migrateUsing());

        $this->app->make(Kernel::class)->setArtisan(null);
    }
}
