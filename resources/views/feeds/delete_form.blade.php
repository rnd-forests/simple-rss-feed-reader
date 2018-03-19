@can ('update', $feed)
    <form action="{{ $feed->path() }}" method="POST" class="ml-a">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-link p-0">
            <i class="fas fa-minus-square fa-lg text-danger" data-toggle="tooltip" data-placement="top" title="Delete the feed"></i>
        </button>
    </form>
@endcan

