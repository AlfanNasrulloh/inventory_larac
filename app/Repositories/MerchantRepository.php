<?php

namespace App\Repositories;

use App\Models\Merchant;

class MerchantRepository
{
    public function getAll(array $fields)
    {
        return Merchant::select($fields)->with(['keeper', 'products.category'])->latest()->paginate(10);
    }

    public function getById(array $fields, int $id)
    {
        return Merchant::select($fields)->with(['keeper', 'products.category'])->latest()->findOrFail($id);
    }

    public function create()
    {
        return Merchant::create();
    }

    public function update(array $data, int $id)
    {
        $merchant = Merchant::findOrFail($data);
        $merchant->update($data);
        return $merchant;
    }

    public function delete(int $id)
    {
        $merchant = Merchant::findOrFail($id);
        $merchant->delete();
    }

    public function getKeeperById(int $keeperId, array $fields = ['*'])
    {
        return Merchant::select($fields)
            ->where('keeper_id', $keeperId)
            ->with(['products.category'])
            ->firstOrFail();
    }
}
