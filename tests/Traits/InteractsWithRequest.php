<?php

declare(strict_types=1);

namespace Tests\Traits;

use Illuminate\Http\Request;

use const null;

trait InteractsWithRequest
{
    protected function getRequest(): Request
    {
        static $request;

        if (null === $request) {
            $request = $this->app->make('request');
        }

        return clone $request;
    }
}
