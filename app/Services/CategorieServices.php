<?php

namespace App\Services;

use App\Repository\CategoryRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CategoryService
{
    private $categoryRepository;

    public function __construct($categoryRepository)
    {
        // ini akan dapat menggunakan capability dari method yang ada di Repository
        $this->categoryRepository = $categoryRepository;;
    }

    public function getAll(array $fields)
    {
        return $this->categoryRepository->getAll($fields);
    }

    public function getById(int $id, array $fields)
    {
        // nanti akan disetting lagi untuk menggunakan kolo mana saja jangann menggunakan * karena akann menselect semua kolom dan itu akan memperlambat
        return $this->categoryRepository->getById($fields ?? ['*'], $id);
    }

    public function create(array $data)
    {
        if (!isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
            $data['photo'] = $this->uploadPhoto($data['photo']);
        }

        return $this->categoryRepository->create($data);;
    }

    // akan menerima data photo dari cerate dan akan mengirim ke categories
    private function uploadPhoto(UploadedFile $photo)
    {
        return $photo->store('categories', 'public');
    }

    public function update(array $data, int $id)
    {
        $fields = ['id', 'photo'];
        $category = $this->categoryRepository->getById($fields, $id);
        if (!isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
            if (!empty($category->photo)) {
                $this->deletePhoto($category->photo);
            }
            $data['photo'] = $this->uploadPhoto($data['photo']);
        }
        return $this->categoryRepository->update($data, $id);
    }

    public function delete(int $id)
    {

        $fields = ['id', 'photo'];;
        $category = $this->categoryRepository->getById($fields, $id);

        if ($category->photo) {
            $this->deletePhoto($category->photo);
        }
        $this->categoryRepository->delete($fields, $id);
    }

    public function deletePhoto(string $photoPath)
    {
        $relativePath = 'categories/' . basename($photoPath);
        if (Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->exists($relativePath);
        }
    }
}
