<?php

namespace Modules\Post\Repositories;

use App\Models\Post;
use App\Models\Category;

class PostRepository
{
    public function getPostsWithCategoryName()
    {
        return Post::select(['title', 'preview', 'image', 'id'])
                        ->addSelect(['category'=>Category::select(['name'])
                                        ->whereColumn('post.category_id', 'id')->limit(1)])->get();
    }

    public function getSinglePost(string $post_id)
    {
        return Post::findOrFail($post_id);
    }
}
