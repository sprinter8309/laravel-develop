<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Modules\User\Services\UserService;
use App\Components\GridViewProvider;

/**
 * Контроллер для выполнения действий над пользователями
 *
 * @author Oleg Pyatin
 */
class UserController extends BaseController
{
    public function __construct(UserService $user_service)
    {
        $this->user_service = $user_service;
    }

    /**
     * Действие вывода окна аутентификации (входа в систему)
     * @return View
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * Действие выполнения аутентификации (пробуем регистрировать пользователя)
     *
     * @param  Request  $request  Входной запрос
     * @return  View
     */
    public function login(Request $request)
    {
        if ($this->user_service->loginAttempt($request)) {
            return redirect()->intended('cabinet')->withSuccess('Вход успешно произведен');
        }

        return redirect("login")->withErrors('Ошибка в данных аутентификации');
    }

    /**
     * Действие вывод окна регистрации
     * @return  View
     */
    public function registration()
    {
        return view('auth.registration');
    }

    /**
     * Действие попытки регистрации (проверяем входные данные на корректность и пробуем зарегать)
     *
     * @param  Request  $request  Входной запрос
     * @return  View
     */
    public function customRegistration(Request $request)
    {
        $this->user_service->registrationAttempt($request);
        return redirect("cabinet")->withSuccess('Аутентификация прошла успешно');
    }

    /**
     * Действие разлогирования (прекращаем авторизованный режим)
     *
     * @param  Request  $request  Входной запрос
     * @return  View
     */
    public function logout(Request $request)
    {
        $this->user_service->logoutActions();
        return Redirect('login');
    }

    /**
     * Действие вывода главного окна в личном кабинете пользователя
     * @return View
     */
    public function cabinet(Request $request)
    {
        if ($this->user_service->checkUserAuthentication()) {

            $exam_attempt_data_provider = GridViewProvider::getDataProvider($this->user_service->getExamAttempts(), $request);

            if ($exam_attempt_data_provider->checkAjaxMode()) {
                return view('auth.cabinet', [
                    'exam_attempt_data_provider'=>$exam_attempt_data_provider
                ]);
            } else {
                return view('auth.cabinet', [
                    'exam_attempt_data_provider'=>$exam_attempt_data_provider
                ]);
            }




//            return view('auth.cabinet', [
//                'attempts'=>$this->user_service->getExamAttempts()
//            ]);
        }

        return redirect("login")->withSuccess('Требуется аутентификация');
    }

    /**
     * Действие просмотра результатов одной из попыток прохождения тестов
     *
     * @param string $attempt_id  ID нужной для просмотра попытки
     * @return View
     */
    public function viewExamAttempt(string $attempt_id)
    {
        $attempt = $this->user_service->getUserExamAttempt($attempt_id);

        $attempt_info = $this->user_service->getUserExamAttemptInfo($attempt);

        return view('auth.exam_attempt', [
            'exam_name'=>$attempt_info->getExamName(),
            'right_answers_amount'=>$attempt_info->getRightAnswersAmount(),
            'questions_total_quantity'=>$attempt_info->getQuestionsTotalQuantity(),
            'mark'=>$attempt_info->getMark(),
            'user_answers'=>$attempt_info->getUserAnswers(),
            'right_answers_percent'=>$attempt_info->getRightAnswersPercent(),
            'exam_questions'=>$attempt_info->getExamQuestions(),
            'answers_show'=>$attempt_info->getAnswersShow(),
            'exam_right_answers_map'=>$attempt_info->getExamRightAnswersMap(),
            'attempt'=>$attempt
        ]);
    }
}