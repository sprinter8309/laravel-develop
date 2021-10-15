<?php

namespace Modules\Post\Services;

use Modules\Post\Repositories\CategoryRepository;
use Illuminate\Database\Eloquent\Collection;

/**
 * Сервис организует логику вывода постов для приложения
 *
 * @author Oleg Pyatin
 */
class CategoryService
{
    public function __construct(CategoryRepository $category_repository)
    {
        $this->category_repository = $category_repository;
    }

    /**
     * Функция используемая для получения полного списка постов для заданной категории
     *
     * @return  Collection  Массив статей для заданной категории
     */
    public function getAllCategoryPosts(string $category_id): Collection
    {
        return $this->category_repository->getAllCategoryPosts($category_id);
    }

    /**
     * Функция получения всех имеющихся категорий статей
     *
     * @return Collection
     */
    public function getAllCategories(): Collection
    {
        return $this->category_repository->getAllCategories();
    }

    /**
     * Получение имени категории по ее ID
     *
     * @param int $category_id  ID категории
     * @return string  Ее текущее название
     */
    public function getCategoryName(int $category_id): string
    {
        return $this->category_repository->getCategoryById($category_id)->name;
    }
}
