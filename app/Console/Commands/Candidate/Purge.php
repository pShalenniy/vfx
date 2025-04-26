<?php

declare(strict_types=1);

namespace App\Console\Commands\Candidate;

use App\Models\Candidate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;

class Purge extends Command
{
    protected $signature = 'candidate:purge {--source=}';

    protected $description = 'Purge candidates';

    public function handle(): int
    {
        $iterator = Candidate::query()
            ->when($this->option('source'), static function ($q, $source) {
                $q->where('source', $source);
            })
            ->lazyById();

        foreach ($iterator as $candidate) {
            try {
                $candidate->delete();
            } catch (Throwable $e) {
                Log::error($e->getMessage(), [
                    'command' => static::class,
                    'id' => $candidate->getKey(),
                ]);
            }
        }

        return self::SUCCESS;
    }
}
