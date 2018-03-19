@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card mb-3">
        <div class="card-body">
            <h2>{{ $feed->title }}</h2>
            <a href="{{ $feed->url }}">{{ $feed->url }}</a>
            @if ($content->get('published_date'))
                <p class="text-dark m-0 p-0">{{ $content->get('published_date') }}</p>
            @endif
        </div>
    </div>

    @foreach ($content->get('entries') as $entry)
        <div class="card mb-2">
            <div class="card-body">
                <div class="card-title"><h4>{{ $entry->getTitle() }}</h4></div>

                @if ($entry->getPublishedDate())
                    <h6 class="mb-2 text-muted">
                        <i class="fas fa-clock mr-1"></i>
                        {{ $entry->getPublishedDate() }}
                    </h6>
                @endif

                @if ($entry->getLink())
                    <h6 class="mb-2 text-muted">
                        <i class="fas fa-link mr-1"></i>
                        <a href="{{ $entry->getLink() }}">{{ $entry->getLink() }}</a>
                    </h6>
                @endif

                @if ($entry->getDescription())
                    <div class="card-text mt-4 p-2">
                        {!! $entry->getDescription() !!}
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection
