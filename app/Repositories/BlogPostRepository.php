<?php

namespace App\Repositories;

use App\Models\BlogPost as Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Class BlogСategoryRepository.
 */
class BlogPostRepository extends CoreRepository
{
    protected function getModelClass(): string
    {
        return Model::class; //абстрагування моделі BlogCategory, для легшого створення іншого репозиторія
    }

    /**
     * Отримати список статей
     *
     * @return LengthAwarePaginator
     */
    public function getAllWithPaginate(): LengthAwarePaginator
    {
        $columns = ['id', 'title', 'slug', 'is_published', 'published_at', 'user_id', 'category_id',];

        return $this->startConditions()
            ->select($columns)
            ->orderBy('id', 'DESC')
            ->with([
                'category' => function ($query) {
                    $query->select(['id', 'title']);
                },
                //'category:id,title',
                'user:id,name',
            ])
            ->paginate(25);
    }

    /**
     *  Отримати модель для редагування в адмінці
     * @param int $id
     * @return Model
     */
    public function getEdit(int $id): Model
    {
        $post = $this->startConditions()->find($id);

        if (!$post) {
            throw new \Exception("Запис з id={$id} не знайдено");
        }

        return $post;
    }
}
