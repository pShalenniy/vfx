<?php

declare(strict_types=1);

namespace App\Http\Controllers\Common;

use App\Repositories\Contracts\Common\UserCompanyRepositoryContract;
use App\Repositories\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use McMatters\ClearbitApi\ClearbitClient;
use Throwable;

use function array_flip;
use function array_merge;
use function preg_replace;
use function trim;

class UserCompanyController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $userCompanies = [];

        $keyword = trim($request->get('company', ''));

        if (empty($keyword)) {
            return new JsonResponse(['companies' => []]);
        }

        try {
            $userCompanies = Factory::make(UserCompanyRepositoryContract::class)->search($keyword);

            $existCompanies = array_flip(Arr::pluck($userCompanies, 'url'));

            $userCompanies = array_merge(
                $userCompanies,
                $this->searchCompaniesInClearbit($keyword, $existCompanies),
            );
        } catch (Throwable $e) {
            Log::error(
                $e->getMessage(),
                ['controller' => static::class, 'method' => __FUNCTION__],
            );
        }

        return new JsonResponse(['companies' => $userCompanies]);
    }

    protected function searchCompaniesInClearbit(
        string $keyword,
        array $existCompanies,
    ): array {
        $companies = [];

        $client = new ClearbitClient();

        $clearbitCompanies = $client->autocomplete()->lookupByCompany($keyword);

        foreach ($clearbitCompanies as $item) {
            $item['url'] = preg_replace('~^www\.~', '', $item['domain']);

            if (isset($existCompanies[$item['url']])) {
                continue;
            }

            unset($item['domain']);

            $companies[] = $item;
        }

        return $companies;
    }
}
