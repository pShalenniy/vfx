<?php

declare(strict_types=1);

namespace App\Http\Controllers\Client;

use App\Helpers\JsHelper;
use App\Http\Resources\Client\ContentDataCollection;
use App\Http\Resources\Client\OurPartnerResource;
use App\Repositories\Contracts\Common\ContentDataRepositoryContract;
use App\Repositories\Factory;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;

class BasicPageController extends Controller
{
    protected ContentDataRepositoryContract $repository;

    public function __construct()
    {
        $this->repository = Factory::make(ContentDataRepositoryContract::class);
    }

    public function aboutUsPage(): ViewContract
    {
        JsHelper::push([
            'aboutUsPage' => new ContentDataCollection(
                Factory::make(ContentDataRepositoryContract::class)->getDataForAboutUsPage(),
            ),
        ]);

        return View::make('client.pages.about-us');
    }

    public function homePage(): ViewContract
    {
        JsHelper::push([
            'page' => new ContentDataCollection(
                $this->repository->getDataForHomePage(),
            ),
            'ourPartner' => OurPartnerResource::collection(
                $this->repository->getOurPartners(),
            ),
        ]);

        return View::make('client.pages.home');
    }

    public function privacyPolicyPage(): ViewContract
    {
        JsHelper::push([
            'privacyPolicy' => new ContentDataCollection(
                $this->repository->getDataForPrivacyPolicyPage(),
            ),
        ]);

        return View::make('client.pages.privacy-policy');
    }

    public function termsAndConditionsPage(): ViewContract
    {
        JsHelper::push([
            'termsAndConditions' => new ContentDataCollection(
                $this->repository->getDataForTermsAndConditionsPage(),
            ),
        ]);

        return View::make('client.pages.terms-and-conditions');
    }
}
