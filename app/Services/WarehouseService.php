<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Repositories\WarehouseRepository;

class WarehouseService
{
    private $warehouseRepository;

    public function __construct($warehouseRepository)
    {
        $this->warehouseRepository = $warehouseRepository;
    }

    public function getAll(array $fields)
    {
        return $this->warehouseRepository->getAll($fields);
    }

    public function getById(int $id, array $fields)
    {
        // nanti akan disetting lagi untuk menggunakan kolo mana saja jangann menggunakan * karena akann menselect semua kolom dan itu akan memperlambat
        return $this->warehouseRepository->getById($fields ?? ['*'], $id);
    }


    public function create(array $data)
    {
        if (!isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
            $data['photo'] = $this->uploadPhoto($data['photo']);
        }

        return $this->warehouseRepository->create($data);;
    }

    // akan menerima data photo dari cerate dan akan mengirim ke categories
    private function uploadPhoto(UploadedFile $photo)
    {
        return $photo->store('categories', 'public');
    }

    public function update(array $data, int $id)
    {
        $fields = ['id', 'photo'];
        $category = $this->warehouseRepository->getById($fields, $id);
        if (!isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
            if (!empty($category->photo)) {
                $this->deletePhoto($category->photo);
            }
            $data['photo'] = $this->uploadPhoto($data['photo']);
        }
        return $this->warehouseRepository->update($data, $id);
    }

    public function delete(int $id)
    {

        $fields = ['id', 'photo'];
        $category = $this->warehouseRepository->getById($fields, $id);

        if ($category->photo) {
            $this->deletePhoto($category->photo);
        }
        $this->warehouseRepository->delete($fields, $id);
    }

    public function deletePhoto(string $photoPath)
    {
        $relativePath = 'warehouses/' . basename($photoPath);
        if (Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->exists($relativePath);
        }
    }

    public function attachProduct(int $warehouseId, int $stock, int $productId)
    {
        $warehouse = $this->warehouseRepository->getById($warehouseId, ['id']);
        $warehouse->products()->syncWithoutDetaching([
            $productId => ['stock' => $stock]
        ]);
    }

    public function detachProduct(int $productId, int $warehouseId)
    {
        $warehouse = $this->warehouseRepository->getById($warehouseId, ['id']);
        $warehouse->products()->detach($productId);
    }

    public function updateProductStock(int $warehouseId, int $stock, int $productId)
    {
        $warehouse = $this->warehouseRepository->getById($warehouseId, ['id']);
        $warehouse->products()->updateExistingPPivot([
            $productId => ['stock' => $stock]
        ]);
        return $warehouse->products()->where('product_id', $productId)->first();
    }
}
