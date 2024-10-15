<?php

namespace App\Services;

use Milenatf\MicroservicesCommon\Services\Traits\ConsumerExternalService;

class EvaluationService
{
    use ConsumerExternalService;

    protected $url, $token;

    public function __construct()
    {
        $this->url = config('services.micro_02.url');
        $this->token = config('services.micro_02.token');
    }

    public function getEvaluationsCompany(string $company)
    {
        $response = $this->request('get', "/evaluations/{$company}");
        // dd($response);

        // dd($response->body());
        return $response->body();
    }
}