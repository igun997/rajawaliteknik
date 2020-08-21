@extends('adminlte::page')

@section('title', $title)

@section('content_header')

@stop

@section('content')
    <div class="row">
        @foreach($data[session()->get("level")] as $menu)
        <div class="col-4">
            <a href="{{$menu["route"]}}" class="btn btn-primary btn-lg btn-block">{{$menu["name"]}}</a>
        </div>
        @endforeach
    </div>
@stop

@section('css')

@stop

@section("js")
    @include("msg")
@stop

