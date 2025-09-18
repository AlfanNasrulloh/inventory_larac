<?php

namespace App\Repository;

use App\Models\Categorie;

class CategoryRepository
{
    public function getAll(array $fields)
    {
        return Categorie::select($fields)->latest()->paginate(50);
    }

    public function getById(array $fields, int $id)
    {
        return Categorie::select($fields)->latest()->findOrFail($id);
    }

    public function create(array $data)
    {
        return Categorie::create($data);
    }

    public function update(array $data, int $id)
    {
        $category = Categorie::findOrFail($id);
        $category->update($data);
        return $category;
    }

    public function delete(array $data, int $id)
    {
        $category = Categorie::findOrFail($id);
        $category->delete();
    }
}
