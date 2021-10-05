<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Modules\User\Services\UserService;

class UserController extends BaseController
{
    public function __construct(UserService $user_service)
    {
        $this->user_service = $user_service;
    }

    
    public function index()
    {
        return view('auth.login');
    }


    public function login(Request $request)
    {
        if ($this->user_service->loginAttempt($request)) {
            return redirect()->intended('cabinet')->withSuccess('Вход успешно произведен');
        }

        return redirect("login")->withErrors('Ошибка в данных аутентификации');
    }


    public function registration()
    {
        return view('auth.registration');
    }


    public function customRegistration(Request $request)
    {
        $this->user_service->registrationAttempt($request);
        return redirect("cabinet")->withSuccess('Аутентификация прошла успешно');
    }


    public function logout(Request $request)
    {
        $this->user_service->logoutActions();
        return Redirect('login');
    }


    public function cabinet()
    {
        if ($this->user_service->checkUserAuthentication()) {
            return view('auth.cabinet');
        }

        return redirect("login")->withSuccess('Требуется аутентификация');
    }
}