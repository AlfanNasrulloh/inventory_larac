<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\WarehouseService;
use Illuminate\Routing\Controller;
use App\Http\Requests\WarehouseProductUpdateRequest;

class WarehouseProductController extends Controller
{
    //
    private $warehouseService;

    public function __construct($warehouseService)
    {
        $this->warehouseService = $warehouseService;
    }

    public function attach(Request $request, int $warehouseId)
    {
        $request->validate([
            'product_id' => 'required|exists:product, id',
            'stock' => 'required|,in:1'
        ]);

        $this->warehouseService->attachProduct([
            $warehouseId,
            $request->input('product_id'),
            $request->input('stock')
        ]);

        return response()->json([
            'message' => 'Product attached successfully!'
        ]);
    }

    public function detach(int $warehouseId, int $productId): JsonResponse
    {
        $this->warehouseService->detachProduct($warehouseId, $productId);
        return response()->json(['message' => 'Product detached successfully!']);
    }

    public function update(WarehouseProductUpdateRequest $request, int $warehouseId, int $productId)
    {
        $warehouseProduct = $this->warehouseService->updateProductStock(
            $warehouseId,
            $productId,
            $request->validate()['stock']
        );

        return response()->json([
            'message' => 'Stock updated successfully!',
            'data' => $warehouseProduct,
        ]);
    }
}
