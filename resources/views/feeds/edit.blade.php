@extends('layouts.app')

@section('content')
<div class="container">
    @include('feeds.form', ['submitBtn' => 'Update RSS Feed', 'refreshUrl' => route('feeds.edit', [$feed])])
</div>
@endsection
