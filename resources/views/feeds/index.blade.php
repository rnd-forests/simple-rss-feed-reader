@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card mb-3">
        <div class="card-body">
            <h4 class="mb-0"><strong>RSS Feed Management</strong></h4>
        </div>
    </div>

    @include('feeds.form', ['submitBtn' => 'Add New RSS Feed', 'refreshUrl' => route('feeds.index')])

    <table class="table bg-white">
        <thead class="thead-dark">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Title</th>
                <th scope="col">URL</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($feeds as $feed)
                <tr>
                    <th scope="row">{{ $feed->id }}</th>
                    <td><a href="{{ $feed->path() }}">{{ $feed->title }}</a></td>
                    <td><a href="{{ $feed->path() }}">{{ $feed->url }}</a></td>
                    <td class="d-flex justify-content-between">
                        <a href="{{ $feed->url }}" target="_blank">
                            <i class="fas fa-external-link-square-alt fa-lg text-info" data-toggle="tooltip" data-placement="top" title="View original feed"></i>
                        </a>

                        <a href="{{ route('feeds.edit', [$feed]) }}">
                            <i class="fas fa-pen-square fa-lg text-primary"></i>
                        </a>

                        @include('feeds.delete_form')
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex flex-row justify-content-center">
        {{ $feeds->render() }}
    </div>
</div>
@endsection
