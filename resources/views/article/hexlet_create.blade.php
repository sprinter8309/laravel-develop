@extends('layouts.app')

@section('header')
    {{ Form::model($article, ['url'=>route('articles.store', $article)]) }}
        {{ Form::label('name', 'Название') }}
        {{ Form::text('name') }}<br>
        {{ Form::label('body', 'Описание') }}
        {{ Form::textarea('body') }}<br>
        {{ Form::submit('Создать') }}
    {{ Form::close() }}
@endsection
