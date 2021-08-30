@extends('layouts.app')

@section('header')
    @include('widgets._high_menu')
@endsection

@section('title-content-label')
    <h2 class='content-title-label'>Войти в систему</h2>
@endsection

@section('content')
    <div class="content-block">
        <form class="auth-form" method="POST" action="{{ route('login.custom') }}">
            @csrf
            <div>
                <h3>Введите свой email</h3>
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
            <input type="submit" value="Войти">
        </form>
    </div>
@endsection
