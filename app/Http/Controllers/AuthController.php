<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;
use App\Components\Constants\UsersConstant;

class AuthController extends BaseController
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'=>'required',
            'password'=>'required'
        ]);

        $credentials = $request->only('email', 'password');

        $result = Auth::attempt($credentials);

        if (Auth::attempt($credentials)) {
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
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6',
            'repeated_password'=>'same:password',
        ]);

        $data = $request->all();
        $new_user = $this->create($data);

        Auth::loginUsingId($new_user->id);

        return redirect("cabinet")->withSuccess('Аутентификация прошла успешно');
    }

    // В отдельный сервис
    public function create(array $data): User
    {
        return User::create([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'password'=>Hash::make($data['password']),
            'status'=>UsersConstant::USER_ACTIVE_STATUS,
            'user_type'=>UsersConstant::USER_BASIC_TYPE,
            'social_networks'=>UsersConstant::USER_EMPTY_SOCIAL_NETWORKS
        ]);
    }

    public function logout(Request $request)
    {
        Session::flush();
        Auth::logout();

        return Redirect('login');
    }

    public function cabinet()
    {
        if (Auth::check()) {
            return view('auth.cabinet');
        }

        return redirect("login")->withSuccess('Требуется аутентификация');
    }
}
