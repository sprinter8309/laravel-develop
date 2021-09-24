<?php

namespace Modules\Post\Repositories;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository
{
    public function getAllCategoryPosts(string $category_id): Collection
    {
        return Post::select(['title', 'preview', 'image', 'id'])
                                ->where('category_id', $category_id)
                                ->addSelect(['category'=>Category::select(['name'])
                                        ->whereColumn('post.category_id', 'id')->limit(1)])->get();
    }
}
