<?php

namespace Modules\User\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Modules\User\Components\UserFactory;
use Modules\User\Repositories\UserRepository;
use Modules\Exam\Services\ExamService;
use Modules\Exam\Entities\ExamResultActions;
use Modules\Exam\Entities\ExamResultData;
use App\Models\ExamAttempt;
use Session;

/*
 * Сервис организует работу с пользователями (аутентификация, регистрация, ЛК)
 *
 * @author Oleg Pyatin
 */
class UserService
{
    public function __construct(UserFactory $user_factory, UserRepository $user_repository, ExamService $exam_service)
    {
        $this->user_factory = $user_factory;
        $this->user_repository = $user_repository;
        $this->exam_service = $exam_service;
    }

    /**
     * Функция авторизации пользователя (с проверкой если нужно)
     *
     * @param Request $request  Входные данные
     * @return void  Авторизуем если все хорошо
     */
    public function loginAttempt(Request $request)
    {
        $request->validate([
            'email'=>'required',
            'password'=>'required'
        ]);

        $credentials = $request->only('email', 'password');

        return Auth::attempt($credentials);
    }

    /**
     * Функция регистрации пользователя (с валидацией корректности регистрационных данных)
     *
     * @param Request $request  Данные для регистрации
     */
    public function registrationAttempt(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6',
            'repeated_password'=>'same:password',
        ]);

        $data = $request->all();
        $new_user = $this->create($data);
        $this->user_repository->saveNewUser($new_user);

        Auth::loginUsingId($new_user->id);
    }

    /**
     * Метод для создания нового пользот еля (через фабрику)
     *
     * @param array $data
     * @return type
     */
    public function create(array $data)
    {
        return $this->user_factory->create($data);
    }

    /**
     * Метод разлогирования (выхода из авторизованного режима)
     */
    public function logoutActions()
    {
        Session::flush();
        Auth::logout();
    }

    /**
     * Функция проверки имеет ли пользователь аутентификацию
     * @return bool
     */
    public function checkUserAuthentication()
    {
        return Auth::check();
    }

    /**
     * Получаем все попытки прохождения тестов у пользователя
     *
     * @return  Collection  Полный список попыток
     */
    public function getExamAttempts(): Collection// array
    {
        $attempts = $this->user_repository->getUserExamAttemptsList(Auth::user()->id);
        return $this->getPreparedAttemptsArray($attempts);
    }

    /**
     * Функция модификации массива попыток прохождения тестов у пользователей (для страниц ЛК)
     *     Модифицируем статус под русские буквы, а также добавляем процент правильных ответов
     *
     * @param Collection $attempts  Первичный массив попыток (в форме коллекции)
     * @return array  Модифицированный для вывода массив
     */
    public function getPreparedAttemptsArray(Collection $attempts): Collection//array
    {
//        $attempts_array = $attempts->toArray() ?? [];
        foreach ($attempts as &$attempt) {

//            dd($attempt);

            // Перевод статуса с внутреннего представления на русский язык
            $attempt["status"] = ExamAttempt::RUSSIAN_STATUS_SIGN[$attempt["status"]];

            // Добавление процента правильно отвеченных вопросов
            $exam = $this->exam_service->getExamById($attempt["exam_id"]) ?? null;

            if (!empty($exam)) {

                $user_answers = json_decode($attempt["user_answers"], true);
                $exam_analyze_result = $this->exam_service->checkRightAnswers($exam->questions, $user_answers);

//                $attempt+=[
//                    'right_answers_percent'=>$exam_analyze_result->getRightAnswersPercent()
//                ];
                $attempt['right_answers_percent'] = $exam_analyze_result->getRightAnswersPercent();

            } else {
                unset($attempt);
            }
        }
        return $attempts;
//        return collect($attempts_array);
    }

    /**
     * Функция простого получения попытки прохождения теста у пользователя
     *
     * @param string $attempt_id  ID попытки
     * @return ExamAttempt  Объект попытки
     */
    public function getUserExamAttempt(string $attempt_id): ExamAttempt
    {
        return $this->user_repository->getUserExamAttempt(Auth::user()->id, $attempt_id);
    }

    /**
     * Получение информации с аналитикой (оценка, число правильныз ответов и др) о заданной попытке теста у пользователя
     *
     * @param ExamAttempt $attempt  Объект попытки
     * @return ExamResultData  Объект для вывода результатов (аналогичен для вывода результатов после прохождения теста)
     */
    public function getUserExamAttemptInfo(ExamAttempt $attempt): ExamResultData
    {
        $user_answers = json_decode($attempt->user_answers, true);
        $exam = $this->exam_service->getExamById($attempt["exam_id"]) ?? null;

        $exam_analyze_result = $this->exam_service->checkRightAnswers($exam->questions, $user_answers);
        $exam_result_actions = ExamResultActions::getFromJson($exam->result_actions);

        $mark = $this->exam_service->getMarkForResult($exam_result_actions, $exam_analyze_result);

        return ExamResultData::loadFromArray([
            'exam_name'=>$exam->name,
            'right_answers_amount'=>$exam_analyze_result->right_answers_amount,
            'questions_total_quantity'=>$exam->questions->count(),
            'mark'=>$mark,
            'user_answers'=>$user_answers,
            'exam_questions'=>$exam->questions,
            'answers_show'=>$exam_result_actions->answers_show,
            'exam_right_answers_map'=>$exam_analyze_result->exam_right_answers_map
        ]);
    }
}
