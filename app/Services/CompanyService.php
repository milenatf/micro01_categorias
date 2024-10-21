<?php

namespace App\Services;

use App\Models\Company;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CompanyService
{
    protected $repository;

    public function __construct(Company $company)
    {
        $this->repository = $company;
    }

    public function getCompanies(string $filter = '')
    {
        return $this->repository->getCompanies($filter);
    }

    public function createNewCompany(array $data, UploadedFile $image)
    {
        $path = $this->uploadImage($image);

        $data['image'] = $path;

        return $this->repository->create($data);
    }

    public function getCompanyByUuid(string $uuid = null)
    {
        return $this->repository->where('uuid', $uuid)->firstOrFail();
    }

    public function updateCompany(string $uuid = '', array $data, UploadedFile $image = null)
    {
        $company = $this->getCompanyByUuid($uuid);

        if($image) {
            // Verifica se ja tem a imagem e deleta o arquivo antigo para não ficar armazenado arquivo desnecessário
            if(Storage::exists($company->image)) {
                Storage::delete($company->image);
            }

            $data['image'] = $this->uploadImage($image); // Faz o upload da imagem
        }

        return $company->update($data);
    }

    public function deleteCompany(string $uuid = null)
    {
        $company = $this->getCompanyByUuid($uuid);

        // Verifica se ja tem a imagem e deleta o arquivo antigo para não ficar armazenado arquivo desnecessário
        if(Storage::exists($company->image)) {
            Storage::delete($company->image);
        }

        return $company->delete();
    }

    private function uploadImage(UploadedFile $image)
    {
        return $image->store('companies'); // Retornar o path relativo
    }
}