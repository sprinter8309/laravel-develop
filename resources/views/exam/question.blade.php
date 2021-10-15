@extends('layouts.exam')

<?php
use app\models\StandartQuestion;
?>

@section('header')
    @include('widgets._high_menu')
@endsection

@section('title-content-label')
    <h2 class='exam-title-label'>Вопрос №{{ $quest_number }}</h2>
@endsection

@section('content')
    <script src="{{ asset("js/exam_question.js") }}" defer></script>

    <div class="exam-question-design-high-line"></div>
    <div class="exam-question-description">
        {{ $quest_text }}
    </div>
    <div class="exam-question-answers-block">

        <form class="exam-question-form" method="POST" action="/main">
            @csrf

            @switch ($quest_type)

                @case(StandartQuestion::SINGLE_CHOICE_QUEST_TYPE)
                    @foreach ($answers as $answer)
                        <label class="exam-question-answers-single-choice">
                            <input name="answer" type="radio" value="{{ $answer["text"] }}"
                                <?php if ($answer["text"]===$current_answer) echo " checked"; ?>>{{ $answer["text"] }}
                        </label>
                    @endforeach
                    @break
                @case(StandartQuestion::MULTIPLE_CHOICE_QUEST_TYPE)
                    @foreach ($answers as $answer)
                        <label class="exam-question-answers-multiple-choice">
                            <input name="answer[]" type="checkbox" value="{{ $answer["text"] }}"
                                <?php if (in_array($answer["text"], $current_answer ?? [])) echo " checked"; ?>>{{ $answer["text"] }}
                        </label>
                    @endforeach
                    @break
                @default
                    @break
            @endswitch

            <div class="exam-question-design-bottom-line"></div>

            <div class="exam-question-info-block">
                <div class="exam-question-answers-amount">Ответов на вопросы: {{ $answers_amount }} из {{ $total_question_amount }}</div>
            </div>

            <div class="exam-question-answers-control-buttons">
                <button class="exam-question-button-begin">В начало</button>
                <button class="exam-question-button-previous">Назад</button>
                <button class="exam-question-button-next">Дальше</button>
                <button class="exam-question-button-finish">Закончить тест</button>
            </div>
            <input name="question_id" type="hidden" value="{{ $quest_id }}">
            <input name="exam_id" type="hidden" value="{{ $exam_id }}">
            <div class="exam-question-technic-info">
                <div class="exam-question-technic-info-quest-id">{{ $quest_id }}</div>
            </div>
        </form>
    </div>
@endsection
