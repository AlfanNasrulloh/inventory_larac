<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MerchantService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\MerchantRequest;
use App\Http\Resources\MerchantResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MerchantController extends Controller
{
    //
    private $merchantService;
    public function __construct($merchantService)
    {
        $this->merchantService = $merchantService;
    }

    public function index()
    {
        $fields = ["*"];
        $merchant = $this->merchantService->getAll($fields = ["*"]);
        return response()->json(MerchantResource::collection($merchant));
    }

    public function show(int $id)
    {
        try {
            $fields = ['id', 'name', 'photo', 'keeper_id'];
            $merchant = $this->merchantService->getById($id, $fields);
            return response()->json(new MerchantResource($merchant));
        } catch (ModelNotFoundException $e) {
            return response()->json(
                [
                    'message' => 'Merchant not found!'
                ],
                404
            );
        }
    }

    public function store(MerchantRequest $request)
    {
        $merchant = $this->merchantService->create($request->validated());
        return response()->json(
            new MerchantResource($merchant),
            201
        );
    }

    public function update(MerchantRequest $request, int $id)
    {
        try {
            $merchant = $this->merchantService->update($id, $request->validated());
            return response()->json(new MerchantResource($merchant));
        } catch (ModelNotFoundException $e) {
            return response()->json(
                [
                    'message' => 'Merchant not found!'
                ],
                404
            );
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->merchantService->delete($id);
            return response()->json([
                'message' => 'Merchant deleted successfully.'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(
                [
                    'message' => 'Merchant not found!'
                ],
                404
            );
        }
    }

    public function getMerchantProfile()
    {
        $userId = Auth::id();

        try {
            $merchant = $this->merchantService->getByKeeperId($userId);
            return response()->json(new MerchantResource($merchant));
        } catch (ModelNotFoundException $e) {
            return response()->json(
                [
                    'message' => 'Merchant not found for this user!'
                ],
                404
            );
        }
    }
}
