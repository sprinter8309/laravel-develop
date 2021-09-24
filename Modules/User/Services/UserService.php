<?php

namespace Modules\User\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Modules\User\Components\UserFactory;
use Modules\User\Repositories\UserRepository;
use Session;

/*
 * Сервис организует работу с пользователями
 *
 * @author Oleg Pyatin
 */
class UserService
{
    public function __construct(UserFactory $user_factory, UserRepository $user_repository)
    {
        $this->user_factory = $user_factory;
        $this->user_repository = $user_repository;
    }

    public function loginAttempt(Request $request)
    {
        $request->validate([
            'email'=>'required',
            'password'=>'required'
        ]);

        $credentials = $request->only('email', 'password');

        return Auth::attempt($credentials);
    }


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


    public function create(array $data)
    {
        return $this->user_factory->create($data);
    }


    public function logoutActions()
    {
        Session::flush();
        Auth::logout();
    }


    public function checkUserAuthentication()
    {
        return Auth::check();
    }
}
