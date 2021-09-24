@extends('layouts.app')

@section('header')
    @include('widgets._high_menu')
@endsection

@section('title-content-label')
    <h2 class='content-title-label'>Тесты</h2>
@endsection

@section('content')
    @foreach ($pair_exams as $pair_exam_item)
        <div class="exam-index-tile-row">
            <div class="exam-tile-left-item">
                <div>
                    {{ $pair_exam_item['left']->name }}
                </div>
                <div>
                    {{ $pair_exam_item['left']->version }}
                </div>
                <div>
                    {{ $pair_exam_item['left']->point_value }}
                </div>
            </div>
            <div class="exam-tile-right-item">
                <div>
                    {{ $pair_exam_item['right']->name }}
                </div>
                <div>
                    {{ $pair_exam_item['right']->version }}
                </div>
                <div>
                    {{ $pair_exam_item['right']->point_value }}
                </div>
            </div>
        </div>
    @endforeach
@endsection
