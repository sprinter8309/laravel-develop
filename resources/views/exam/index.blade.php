@extends('layouts.app')

@section('header')
    @include('widgets._high_menu')
@endsection

@section('title-content-label')
    <h2 class='content-title-label'>Тесты</h2>
@endsection

@section('content')
    <div class="exam-index-tile-block">
        @foreach ($pair_exams as $pair_exam_item)
            <div class="exam-index-tile-row">
                @if (!empty($pair_exam_item['left']))
                    <a class="exam-tile-left-item" href="{{ route('exam.preview', ['exam_url'=>$pair_exam_item['left']->url]) }}">
                        <img src="{{ $pair_exam_item['left']->preview_img }}">
                        <div class="exam-tile-info-panel">
                            <div class="exam-tile-info-panel-name">
                                {{ $pair_exam_item['left']->name }}
                            </div>
                            <div class="exam-tile-info-panel-value">
                                Количество баллов: {{ $pair_exam_item['left']->point_value }}
                            </div>
                        </div>
                    </a>
                @endif

                @if (!empty($pair_exam_item['right']))
                    <a class="exam-tile-right-item" href="{{ route('exam.preview', ['exam_url'=>$pair_exam_item['right']->url]) }}">
                        <img src="{{ $pair_exam_item['right']->preview_img }}">
                        <div class="exam-tile-info-panel">
                            <div class="exam-tile-info-panel-name">
                                {{ $pair_exam_item['right']->name }}
                            </div>
                            <div class="exam-tile-info-panel-value">
                                Количество баллов: {{ $pair_exam_item['right']->point_value }}
                            </div>
                        </div>
                    </a>
                @endif
            </div>
        @endforeach
    </div>
@endsection
