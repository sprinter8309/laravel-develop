@extends('layouts.app')

@section('header')
    @include('widgets._high_menu')
@endsection

@section('title-content-label')
    <h2 class='content-title-label'>Регистрация</h2>
@endsection

@section('content')
    <div class="content-block">
        <form class="auth-form" method="POST" action="{{ route('registration.custom') }}">
            @csrf
            <div>
                <h3>Введите свой логин</h3>
                <input type="text" class="auth-form-input" name="name" required>
                @if ($errors->has('email'))
                    <span>{{$errors->first('email')}}</span>
                @endif
            </div>
            <div>
                <h3>Введите свой электронный адрес</h3>
                <input type="text" class="auth-form-input" name="email" required>
                @if ($errors->has('email'))
                    <span>{{$errors->first('email')}}</span>
                @endif
            </div>
            <div>
                <h3>Введите свой пароль</h3>
                <input type="password" class="auth-form-input" name="password" required>
                @if ($errors->has('password'))
                    <span>{{$errors->first('password')}}</span>
                @endif
            </div>
            <div>
                <h3>Повторите свой пароль</h3>
                <input type="password" class="auth-form-input" name="repeat_password" required>
                @if ($errors->has('password'))
                    <span>{{$errors->first('password')}}</span>
                @endif
            </div>
            <input type="submit" value="Отправить данные">
        </form>
    </div>
@endsection
