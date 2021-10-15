@include('widgets._simple_grid_view', [
    'data_provider'=>$post_index_data_provider,
    'page_size'=>8,
    'name_basic_route'=>'admin.posts',
    'pagination_class'=>'admin-posts-pagination-block',
    'table_class'=>'cabinet-grid-view',
    'columns'=>[
        [
            'label'=>'ID',
            'name'=>'id',
            'type'=>'value'
        ],
        [
            'label'=>'Имя статьи',
            'name'=>'title',
            'type'=>'value'
        ],
        [
            'label'=>'Категория',
            'name'=>'category',
            'type'=>'value'
        ],
        [
            'label'=>'',
            'name'=>'id',
            'type'=>'code',
            'code'=> function ($value) {
            return '<span class="posts-index-grid-column-action">
                        <a href="/admin/posts/update/'.$value.'"><i class="fas fa-pencil-alt"></i></a>
                    </span>
                    <span class="posts-index-grid-column-action">
                        <a href="/admin/posts/delete/'.$value.'" class="posts-index-action-delete"><i class="fas fa-minus-circle"></i></a>
                    </span>';
            }
        ],
    ]
])