<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Кругозор</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="csrf-param" content="_token" />
        <link href="{{ asset('css/fonts.css') }}" rel="stylesheet">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <script src="{{ asset('js/app.js') }}"></script>
    </head>
    <body>
        <div class="page">
            <div class="center">
                <header>
                    <div class="header-logo">
                        <a href="/">
                            <img src="{{ asset('image/base/logo.png') }}"><!--
                            --><div class="header-text">
                                <span class="header-logo-name">Кругозор</span>
                                <span class="header-logo-label">Блог обо всем</span>
                            </div>
                        </a>
                    </div>
                    <div class="auth-block">
                        @auth
                            <span>{{ Auth::user()->name }}</span>
                            <a href="/logout" class="auth-link">Выход</a>
                        @endauth
                        @guest
                            <a href="/login" class="auth-link">Вход</a>
                            <a href="/registration" class="auth-link">Регистрация</a>
                        @endguest
                    </div>
                </header>
                <section class="header-decor-block clearfix">
                    <div class="header-decor-right">

                    </div>
                    <div class="header-decor-center main-block">

                    </div>
                </section>
                <main>
                    <div class="main-block">
                        @yield('header')
                    </div>
                    <div class="main-block">
                        @yield('content')
                    </div>
                </main>
            </div>
            <footer class="clearfix">
                <div class="footer-decor-left">

                </div>
                <div class="footer-decor-center main-block">
                    <div class="footer-info">
                        Кругозор &copy; 2021
                    </div>
                </div>
                <div class="footer-cover-panel">

                </div>
            </footer>
        </div>
        <div class="back-cover">
            <div class="back-cover-panel-far-left-high">

            </div>
            <div class="back-cover-panel-far-left-low">

            </div>
            <div class="back-cover-panel-near-left-high">

            </div>
            <div class="back-cover-panel-near-left-low">

            </div>

            <div class="back-cover-panel-center-high">

            </div>

            <div class="back-cover-panel-near-right-high">

            </div>
            <div class="back-cover-panel-near-right-low">

            </div>
            <div class="back-cover-panel-far-right-high">

            </div>
            <div class="back-cover-panel-far-right-low">

            </div>
        </div>
        <div class="back-base-cover">
            <div class="back-cover-panel-far-left-column">

            </div>
            <div class="back-cover-panel-near-left-column">

            </div>
            <div class="back-cover-panel-near-right-column">

            </div>
            <div class="back-cover-panel-far-right-column">

            </div>
        </div>
    </body>
</html>
