@extends('layouts.app')

@section('header')
    @include('widgets._high_menu')
@endsection

@section('title-content-label')
    <h2 class='content-title-label'>Административная панель</h2>
@endsection

@section('content')
    <div class="admin-index-menu">
        <a href="{{ route("admin.posts") }}">Создание и редактирование статей</a>
    </div>
@endsection
