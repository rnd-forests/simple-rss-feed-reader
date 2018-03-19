@extends('layouts.app')

@section('content')
<div class="container">
    <div class="jumbotron">
        <h1 class="display-4">Simple RSS Feed Reader</h1>
        <p class="lead">This is a simple application that reads and displays RSS feeds (RSS, Atom, etc.)</p>
        <hr class="my-4">
        @guest
            <p class="lead">
                <a class="btn btn-success" href="{{ route('login') }}">Sign In</a>
                <a class="btn btn-primary" href="{{ route('register') }}">Sign Up</a>
            </p>
        @else
            <p class="lead">
                <a class="btn btn-primary" href="{{ route('feeds.index') }}">View Your Feeds</a>
            </p>
        @endguest
    </div>
</div>
@endsection
