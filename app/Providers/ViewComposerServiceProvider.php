<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\Contracts\Common\ContentDataRepositoryContract;
use App\Repositories\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        /** @var \App\Repositories\Contracts\Common\ContentDataRepositoryContract $repository */
        $repository = Factory::make(ContentDataRepositoryContract::class);

        ViewFacade::composer(
            ['layouts.partials.footer', 'client.pages.contact-us'],
            function (View $view) use ($repository) {
                $view->with('contactData', $this->getContentData($repository->getContactData()));
            },
        );

        ViewFacade::composer(
            ['layouts.partials.header', 'layouts.partials.footer'],
            function (View $view) use ($repository) {
                $view->with('logoData', $this->getContentData($repository->getLogoData()));
            },
        );

        ViewFacade::composer(
            'layouts.partials.social-media-links',
            function (View $view) use ($repository) {
                $view->with('socialData', $this->getContentData($repository->getSocialData()));
            },
        );
    }

    protected function getContentData(Collection $data): array
    {
        $items = [];

        /** @var \App\Models\ContentData $contentData */
        foreach ($data as $contentData) {
            $items[$contentData->getAttribute('key')] = $contentData->getValueAttribute();
        }

        return $items;
    }
}
