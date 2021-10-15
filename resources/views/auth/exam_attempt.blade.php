@extends('layouts.app')

<?php
use Modules\Exam\Entities\ExamResult;
$question_counter = 1;
?>

@section('header')
    @include('widgets._high_menu')
@endsection

@section('title-content-label')
    <h2 class='content-title-label'>Прохождение теста "{{ $attempt->exam_name }}"</h2>
@endsection

@section('content')
    <div class="cabinet-attempt-container">
        <div class="cabinet-attempt-line-info">
            Версия экзамена: {{ $attempt["exam_version"] }}
        </div>
        <div class="cabinet-attempt-line-info">
            Экзамен начат в: {{ date_create($attempt["created_at"])->format("H:i   (d.m.Y)") }}
        </div>
        <div class="cabinet-attempt-line-info">
            Экзамен закончен в: {{ date_create($attempt["finish_at"])->format("H:i   (d.m.Y)") }}
        </div>

        @if ($answers_show !== ExamResult::ANSWERS_NO_SHOW)
            <div class="cabinet-attempt-question-answer-block">
                <h4>Полученные ответы на вопросы:</h4>

                @foreach ($exam_questions as $question)
                    <div class="cabinet-attempt-question-answer-item">
                        <div class="cabinet-attempt-question-item-line">
                            Вопрос №<?= $question_counter++ ?> - {{ $question->quest_text }}
                        </div>
                        <div class="cabinet-attempt-answer-item-line">
                            @switch ($answers_show)
                                @case(ExamResult::ANSWERS_SIMPLE_SHOW)
                                    Ответ - {{ $user_answers[$question->id] }}
                                    @break

                                @case(ExamResult::ANSWERS_RIGHT_MARK_SHOW)
                                    Ответ -
                                    <?php if ($exam_right_answers_map[$question->id]["right"]) {
                                              echo $user_answers[$question->id];
                                          } else {
                                              echo '<span class="exam-result-wrong-answer">' . $user_answers[$question->id];
                                              if (!empty($exam_right_answers_map[$question->id]["right_answer"])) {
                                                  echo ' (ошибка)</span>';
                                              } else {
                                                  echo '</span>';
                                              }
                                          } ?>
                                    @break

                                @case(ExamResult::ANSWERS_WRONG_WITH_RIGHT)
                                    Ответ -
                                    <?php if ($exam_right_answers_map[$question->id]["right"]) {
                                              echo $user_answer_counter++." - ". $user_answers[$question->id];
                                          } else {
                                              echo '<span class="exam-result-wrong-answer">' . $user_answer_counter++." - ". $user_answers[$question->id];

                                              if (!empty($exam_right_answers_map[$question->id]["right_answer"])) {
                                                  echo ' (ошибка)</span>';
                                                  echo ' <span class="exam-result-right-notice">(правильный ответ - '. $exam_right_answers_map[$question->id]["right_answer"] .')</span>';
                                              } else {
                                                  echo '</span>';
                                              }
                                          } ?>
                                    @break

                                @default
                                    @break
                            @endswitch
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="cabinet-attempt-line-info">
            Количество правильных ответов: {{ $right_answers_amount }} из {{ $questions_total_quantity }} ({{$right_answers_percent}} %)
        </div>
        <div class="cabinet-attempt-mark">
            Итоговая оценка: {{ $mark }}
        </div>

        <a class="cabinet-attempt-button" href="{{ route('cabinet') }}">В личный кабинет</a>
    </div>
@endsection
