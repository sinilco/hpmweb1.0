@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>You Logged in as {{ $user->name }}</h1>
@stop

@section('content')
    <p>Welcome to this beautiful admin panel.</p>
@stop

@section('js')
    <script></script>
@stop
