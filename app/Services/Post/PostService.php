<?php

namespace App\Services\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Post;

class PostService
{
    public function create(array $data): Post
    {
        return Post::create($data);
    }

    public function find(int $id): Post
    {
        return Post::findOrFail($id);
    }

    public function getAll(array $filters = []): LengthAwarePaginator
    // este metodo trae los ultimos 10 registros
    {
        $query = Post::latest();
        if(!empty($filters['status'])){
            $query->where('status', $filters['status']);
        }
        return $query->paginate(Post::PAGINATE); // traer los ultimos 10 registros
    }

    public function update(int $id, array $data): bool
    {
        return Post::where('id', $id)->update($data);
    }

    public function delete(int $id): bool
    {
        return Post::where('id', $id)->delete();
    }
}