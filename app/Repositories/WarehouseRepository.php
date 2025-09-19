<?php

namespace App\Repositories;

use App\Models\Warehouse;

class WarehouseRepository
{
    public function getAll(array $fields)
    {
        return Warehouse::select($fields)->with(['products.category'])->latest()->paginate();
    }

    public function getById(int $id, array $fields)
    {
        return Warehouse::select($fields)->with(['products.category'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return Warehouse::create($data);
    }

    public function update(array $data)
    {
        $warehouse = Warehouse::findOfFail($data);
        $warehouse->update($data);
        return $warehouse;
    }

    public function destroy(int $id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->delete();
    }
}
