<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateCompany;
use App\Http\Resources\CompanyResource;
use App\Jobs\CompanyCreatedJob;
// use App\Models\Company;
use App\Services\CompanyService;
use App\Services\EvaluationService;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function __construct(
        protected EvaluationService $evaluationService,
        protected CompanyService $companyService
    ) {

    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $companies = $this->companyService->getCompanies($request->get('filter', ''));

        if(!$companies) return response()->json('Not found.', 404);

        return response()->json($companies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateCompany $request)
    {
        $company = $this->companyService->createNewCompany($request->validated(), $request->file('image'));

        if(!$company) {
            return response()->json(['status' => 'failed', 'message' => 'Não foi possível criar a empresa.'], 500);
        }

        CompanyCreatedJob::dispatch($company->email)->onQueue('queue_email');

        return new CompanyResource($company);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $company = $this->companyService->getCompanyByUuid($uuid);

        if(!$company) return response()->json(['message' => 'Company not found'], 404);

        $evaluations = json_decode($this->evaluationService->getEvaluationsCompany($uuid));

        return (new CompanyResource($company))
                    ->additional([
                        'evaluations' => $evaluations->data
                    ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateCompany $request, string $uuid)
    {
        $this->companyService->updateCompany( $uuid, $request->validated(), $request->image);

        return response()->json(['status' => 'Updated'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        $this->companyService->deleteCompany($uuid);

        return response()->json('A empresa foi excluída', 200);
    }
}
