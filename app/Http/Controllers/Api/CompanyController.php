<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateCompany;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function __construct(protected Company $repository)
    {
        
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $companies = $this->repository->with('category')->get();
        $companies = $this->repository->getCompanies($request->get('filter', ''));

        if(!$companies) return response()->json('Not found.', 404);

        return response()->json($companies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateCompany $request)
    {
        $company = $this->repository->create($request->validated());

        return new CompanyResource($company);
        // $company = $this->companyService->createNewCompany($request->validated(), $request->image);

        // CompanyCreated::dispatch($company->email)
        //                     ->onQueue('queue_email');

        // return new CompanyResource($company);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $company = $this->repository->where('uuid', $uuid)->firstOrFail();

        if(!$company) return response()->json(['message' => 'Company not found'], 404);

        return new CompanyResource($company);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateCompany $request, string $uuid)
    {
        $company = $this->repository->where('uuid', $uuid)->first();

        if(!$company) return response()->json(['status' => 'Not found'], 404);

        try  {
            $company->update($request->validated());

            return response()->json(['status' => 'success'], 200);
        } catch(\Exception $e) {

            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        $company = $this->repository->where('uuid', $uuid)->first();

        if(!$company) return response()->json('Company not found', 404);

        try {
            $company->delete();

            return response()->json('A empresa foi excluída', 200);
        } catch(\Exception $e) {
            return response()->json('Não foi possível excluir a empresa', 500);
        }
    }
}
