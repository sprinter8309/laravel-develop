<?php

namespace Modules\Post\Repositories;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;

/**
 * Репозиторий для выполнения работа с постами в аспекте их категорий
 *
 * @author Oleg Pyatin
 */
class CategoryRepository
{
    /**
     * Функция получения всех статей заданной категории (с добавлением названия категории)
     *
     * @param string $category_id  ID нужной категории
     * @return Collection Список всех ее статей
     */
    public function getAllCategoryPosts(string $category_id): Collection
    {
        return Post::select(['title', 'preview', 'image', 'id'])
                                ->where('category_id', $category_id)
                                ->where('is_delete', 'f')
                                ->addSelect(['category'=>Category::select(['name'])
                                        ->whereColumn('post.category_id', 'id')->limit(1)])->get();
    }

    /**
     * Функция получения всех категорий статей
     *
     * @return Collection Коллекция с категориями
     */
    public function getAllCategories(): Collection
    {
        return Category::get();
    }

    /**
     * Функция получения одиночной категории по ID
     *
     * @param int $category_id  ID нужной категории
     * @return Category Возвращаем объект категории
     */
    public function getCategoryById(int $category_id): Category
    {
        return Category::findOrFail($category_id);
    }
}
