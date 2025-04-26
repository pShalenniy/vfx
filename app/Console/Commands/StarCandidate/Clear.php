<?php

declare(strict_types=1);

namespace App\Console\Commands\StarCandidate;

use App\Models\StarCandidate;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;

class Clear extends Command
{
    protected $signature = 'star-candidate:clear';

    protected $description = 'Clear star candidates with ended period';

    public function handle(): int
    {
        $iterator = StarCandidate::query()
            ->where('end_period', '<', Carbon::now())
            ->lazyById();

        foreach ($iterator as $starCandidate) {
            try {
                $starCandidate->delete();
            } catch (Throwable $e) {
                Log::error($e->getMessage(), [
                    'command' => static::class,
                    'id' => $starCandidate->getKey(),
                ]);
            }
        }

        return self::SUCCESS;
    }
}
