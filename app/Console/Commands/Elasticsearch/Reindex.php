<?php

declare(strict_types=1);

namespace App\Console\Commands\Elasticsearch;

use ElasticsearchService;
use Illuminate\Console\Command;
use McMatters\Helpers\Helpers\ServerHelper;

use const null;

class Reindex extends Command
{
    protected $signature = 'elasticsearch:reindex
        {--index=}
        {--model=}
        {--with-trashed}
        {--without-progress}
    ';

    protected $description = 'Reindex all models in elasticsearch';

    public function handle(): int
    {
        ServerHelper::longProcesses();

        $index = $this->option('index');

        ElasticsearchService::clearIndex($index);

        ElasticsearchService::indexAll(
            $this->option('model'),
            $index,
            $this->option('with-trashed'),
            $this->option('without-progress') ? null : $this->output->createProgressBar(),
        );

        $this->info('All models pushed to queue for reindexing');

        return self::SUCCESS;
    }
}
