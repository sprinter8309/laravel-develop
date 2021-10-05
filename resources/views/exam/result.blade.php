@extends('layouts.exam')

<?php
use Modules\Exam\Entities\ExamResult;

$user_answer_counter = 1;
?>

@section('header')
    @include('widgets._high_menu')
@endsection

@section('title-content-label')
    <h2 class='content-title-label'>Результаты теста "{{ $exam_name }}"</h2>
@endsection

@section('content')
    <div class="exam-result-data-container">
        @switch ($answers_show)
            @case(ExamResult::ANSWERS_NO_SHOW)
                @break

            @case(ExamResult::ANSWERS_SIMPLE_SHOW)
                <div class="exam-result-user-answers">
                    <h3>Полученные ответы:</h3>
                    @foreach ($exam_questions as $question)
                        <div class="exam-result-answer-line">
                            <?php echo $user_answer_counter++." - ". ($user_answers[$question->id] ?? "нет ответа"); ?>
                        </div>
                    @endforeach
                </div> @break

            @case(ExamResult::ANSWERS_RIGHT_MARK_SHOW)
                <div class="exam-result-user-answers">
                    <h3>Полученные ответы:</h3>
                    @foreach ($exam_questions as $question)
                        <div class="exam-result-answer-line">
                            <?php if ($exam_right_answers_map[$question->id]["right"]) {
                                    echo $user_answer_counter++." - ". ($user_answers[$question->id] ?? "нет ответа");
                                } else {
                                    echo '<span class="exam-result-wrong-answer">' . $user_answer_counter++." - ". ($user_answers[$question->id] ?? "нет ответа");

                                    if (!empty($exam_right_answers_map[$question->id]["right_answer"])) {
                                        echo ' (ошибка)</span>';
                                    } else {
                                        echo '</span>';
                                    }
                                } ?>
                        </div>
                    @endforeach
                </div> @break

            @case(ExamResult::ANSWERS_WRONG_WITH_RIGHT)
                <div class="exam-result-user-answers">
                    <h3>Полученные ответы:</h3>
                    @foreach ($exam_questions as $question)
                        <div class="exam-result-answer-line">
                            <?php
                                if ($exam_right_answers_map[$question->id]["right"]) {
                                    echo $user_answer_counter++." - ". ($user_answers[$question->id] ?? "нет ответа");
                                } else {
                                    echo '<span class="exam-result-wrong-answer">' . $user_answer_counter++." - ". ($user_answers[$question->id] ?? "нет ответа");

                                    if (!empty($exam_right_answers_map[$question->id]["right_answer"])) {
                                        echo ' (ошибка)</span>';
                                        echo ' <span class="exam-result-right-notice">(правильный ответ - '. $exam_right_answers_map[$question->id]["right_answer"] .')</span>';
                                    } else {
                                        echo '</span>';
                                    }
                                }
                            ?>
                        </div>
                    @endforeach
                </div> @break

            @default
                @break
        @endswitch

        <div class="exam-result-info-line">
            Правильных ответов: {{ $right_answers_amount }} из {{ $questions_total_quantity }} ({{ $right_answers_percent }}%)
        </div>
        <div class="exam-result-info-line">
            Итоговая оценка: {{ $mark }}
        </div>

        <div class="exam-result-control-buttons">
            <a class="exam-result-index-button" href="{{ route('exam') }}">Вернуться к списку тестов</a>
        </div>
    </div>
@endsection
