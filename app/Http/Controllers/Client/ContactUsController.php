<?php

declare(strict_types=1);

namespace App\Http\Controllers\Client;

use App\Helpers\JsHelper;
use App\Http\Requests\Client\ContactUs\PostRequest;
use App\Http\Resources\Client\ContentDataCollection;
use App\Mail\Client\ContactUs\ContactUsMail;
use App\Models\EmailTemplateSetting;
use App\Repositories\Contracts\Admin\EmailTemplateSettingRepositoryContract;
use App\Repositories\Contracts\Common\ContentDataRepositoryContract;
use App\Repositories\Factory;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;

use const null;

class ContactUsController extends Controller
{
    public function view(): ViewContract
    {
        JsHelper::push([
            'page' => new ContentDataCollection(
                Factory::make(ContentDataRepositoryContract::class)->getDataForContactUsPage(),
            ),
        ]);

        return View::make('client.pages.contact-us');
    }

    public function post(PostRequest $request): JsonResponse
    {
        $emails = Factory::make(EmailTemplateSettingRepositoryContract::class)->getEmails(
            EmailTemplateSetting::KEY_CLIENT_CONTACT_US,
        );

        foreach ($emails as $email) {
            Mail::to($email)->send(new ContactUsMail($request->validated()));
        }

        return new JsonResponse(null, 204);
    }
}
