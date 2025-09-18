<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CategoryService;
use Illuminate\Routing\Controller;
use App\Http\Requests\CategorieRequest;
use App\Http\Resources\CategorieResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategorieController extends Controller
{
    //impoert service
    private $categoryService;

    public function __construct($categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $fields = ['id', 'photo', 'name', 'tagline'];
        $categories = $this->categoryService->getAll($fields);
        return response()->json(CategorieResource::collection($categories));;
    }

    public function show(int $id)
    {
        try {
            $fields = ['id', 'name', 'photo', 'tagline'];
            $category = $this->categoryService->getById($fields, $id);
            return response()->json(new CategorieResource($category));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Category Not Found!'
            ], 404);
        }
    }

    public function store(CategorieRequest $request)
    {
        $category = $this->categoryService->create($request->validate());
        return response()->json(new CategorieResource($category), 201);
    }

    public function destroy(int $id)
    {
        try {
            $this->categoryService->delete($id);
            return response()->json([
                'message' => 'Category Deleted Successffully!'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Category Not Found!'
            ], 404);
        }
    }
}
