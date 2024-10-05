<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateCategory;
use App\Http\Resources\CategoryResouce;
use App\Models\Category;
use Illuminate\Http\Request;


class CategoryController extends Controller
{
    public function __construct(protected Category $repository) { }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = $this->repository->get();

        return CategoryResouce::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateCategory $request)
    {
        $category = $this->repository->create($request->validated());

        return new CategoryResouce($category);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $url)
    {
        $category = $this->repository->where('url', $url)->first();

        if(!$category) return response()->json(['status' => 'Not found', 'message' => 'Categoria não encontrada.'], 404);

        return new CategoryResouce($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateCategory $request, string $url)
    {
        $category = $this->repository->where('url', $url)->first();

        if(!$category) return response()->json(['status' => 'Not found'], 404);

        try  {
            $category->update($request->validated());

            return response()->json(['status' => 'success'], 200);
        } catch(\Exception $e) {

            return response()->json(['status' => 'error'], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $url)
    {
        $category = $this->repository->where('url', $url)->first();

        if(!$category->delete()) {
            return response()->json(['status' => 'failed', 'message' => 'Não foi possivel excluir a categoria.'], 500);
        }

        return response()->json(['status' => 'success', 'message' => 'Categoria excluída com sucesso!'], 200);

    }
}
