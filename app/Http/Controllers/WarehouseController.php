<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WarehouseService;
use Illuminate\Routing\Controller;
use App\Http\Resources\WarehouseResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class WarehouseController extends Controller
{
    //
    private $warehouseService;

    public function __construct($warehouseService)
    {
        $this->warehouseService = $warehouseService;
    }

    public function index()
    {
        $fields = ['id', 'photo', 'name'];
        $warehouse = $this->warehouseService->getAll($fields);
        return response()->json(WarehouseResource::collection($warehouse));
    }

    public function show(int $id)
    {
        try {
            $fields = ['id', 'name', 'photo'];
            $warehouse = $this->warehouseService->getById($fields, $id);
            return response()->json(new WarehouseResource($warehouse));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Warehouse Not Found!'
            ], 404);
        }
    }

    public function store($request, int $id)
    {
        $warehouse = $this->warehouseService->create($request->validates());
        return response()->json(new WarehouseResource($warehouse), 201);
    }

    public function update($request, int $id)
    {
        try {
            $warehouse = $this->warehouseService->update($id, $request->validated());
            return response()->json(new WarehouseResource($warehouse));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => "Warehouse Not Found!"
            ], 404);
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->warehouseService->delete($id);
            return response()->json([
                'message' => 'Warehouse Deleted Successfully!'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Warehouse Not Found!'
            ], 404);
        }
    }
}
