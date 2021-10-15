@extends('layouts.exam')

@section('header')
    @include('widgets._high_menu')
@endsection

@section('title-content-label')
    <h2 class='content-title-label'><?php echo $name; ?></h2>
@endsection

@section('content')

    <div class="exam-preview-simple-info-block">
        <div class="exam-preview-params-block">
            <div>Версия: {{ $version }}</div>
            <div>Стоимость (в баллах): {{ $point_value }}</div>
            <div>Ограничение по времени: {{ $time_limit }}</div>
            <div>Количество вопросов: {{ $questions_amount }}</div>
            <div>Категория: {{ $category_exam }}</div>
        </div>
        <div class="exam-preview-image-block">
            <img src="{{ asset($preview_img) }}">
        </div>
    </div>

    <div class="exam-preview-description">{{ $description }}</div>

    <div class="exam-preview-control-buttons">
        @if (!$in_process)
            <a href="/exam/start/{{ $id }}" class="exam-control-button">Начать тест</a>
        @else
            <a href="/exam/continue/{{ $id }}" class="exam-control-button">Продолжить тест</a>
        @endif
    </div>
@endsection
