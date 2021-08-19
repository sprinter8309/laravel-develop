@extends('layouts.app')

@section('header')
    <h1>{{$article->name}}</h1>
    <div>{{$article->body}}</div>
@endsection
