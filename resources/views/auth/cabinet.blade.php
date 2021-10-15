@extends('layouts.app')

@section('header')
    @include('widgets._high_menu')
@endsection

@section('title-content-label')
    <h2 class='content-title-label'>Личный кабинет</h2>
@endsection

<script src="{{ asset("js/simple_grid_view.js") }}" defer></script>

@section('content')
    <div>
        <div class="user-cabinet-attempt-container">
            <h3>Мои попытки тестов</h3>
                @include('widgets._simple_grid_view', [
                    'data_provider'=>$exam_attempt_data_provider,
                    'page_size'=>4,
                    'name_basic_route'=>'cabinet',
                    'table_class'=>'cabinet-grid-view',
                    'pagination_class'=>'admin-posts-pagination-block',
                    'columns'=>[
                        [
                            'label'=>'Имя теста',
                            'name'=>'exam_name',
                            'type'=>'value'
                        ],
                        [
                            'label'=>'Версия',
                            'name'=>'exam_version',
                            'type'=>'value'
                        ],
                        [
                            'label'=>'Статус',
                            'name'=>'status',
                            'type'=>'value'
                        ],
                        [
                            'label'=>'Время завершения',
                            'name'=>'finish_at',
                            'type'=>'code',
                            'code'=> function ($value) {
                                return date_create($value)->format("d.m.Y  H:i");
                            }
                        ],
                        [
                            'label'=>'Правильных ответов (в %)',
                            'name'=>'right_answers_percent',
                            'type'=>'value'
                        ],
                        [
                            'label'=>'',
                            'name'=>'id',
                            'type'=>'code',
                            'code'=> function ($value) {
                                return '<a href="/cabinet/exam_attempt/'.$value.'">Подробнее</a>';
                            }
                        ],
                    ]
                ])
        </div>
    </div>
@endsection
