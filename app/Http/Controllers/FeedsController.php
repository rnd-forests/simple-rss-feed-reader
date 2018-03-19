<?php

namespace App\Http\Controllers;

use App\Feed;
use App\Components\Form;
use App\Components\Grid;
use Illuminate\Http\Request;
use App\Components\Feed\Reader;
use App\Http\Requests\FeedStore;
use App\Http\Requests\FeedUpdate;
use App\Exceptions\InvalidFeedException;

class FeedsController extends Controller
{
    /**
     * Creates new FeedsController instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Displays the list of RSS feeds for current user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $form = new Form([
            'url' => $this->action('store'),
            'method' => 'PUT',
        ]);

        $feeds = (new Grid(['controller' => $this]))
            ->query($request->user()->latestFeeds())
            ->paginate();

        return view('feeds.index', compact('form', 'feeds'));
    }

    /**
     * Displays a given feed.
     *
     * @param  \App\Feed $feed
     * @return \Illuminate\Http\Response
     */
    public function show(Feed $feed)
    {
        $this->authorize('view', $feed);

        try {
            $content = app(Reader::class)->readFeed($feed);

            return view('feeds.show', compact('feed', 'content'));
        } catch (InvalidFeedException $e) {
            return redirect()->route('feeds.index')
                ->with([
                    'level' => 'danger',
                    'flash' => 'Cannot read the feed from provided URL',
                ]);
        }
    }

    /**
     * Stores new RSS feed.
     *
     * @param  \App\Http\Requests\FeedStore $request
     * @return \Illuminate\Http\Response
     */
    public function store(FeedStore $request)
    {
        $feed = new Feed($request->only(['title', 'url']));
        $request->user()->feeds()->save($feed);

        return redirect()->route('feeds.index')
            ->with('flash', 'Your RSS feed has been added.');
    }

    /**
     * Loads form to edit the feed.
     *
     * @param  \App\Feed $feed
     * @return \Illuminate\Http\Response
     */
    public function edit(Feed $feed)
    {
        $this->authorize('update', $feed);

        $form = new Form([
            'url' => $this->action('update', [$feed]),
            'method' => 'PATCH',
            'model' => $feed,
        ]);

        return view('feeds.edit', compact('form', 'feed'));
    }

    /**
     * Updates an existing feed.
     *
     * @param  \App\Http\Requests\FeedUpdate $request
     * @param  \App\Feed $feed
     * @return \Illuminate\Http\Response
     */
    public function update(FeedUpdate $request, Feed $feed)
    {
        $this->authorize('update', $feed);

        $feed->update($request->only('title', 'url'));

        return redirect()->route('feeds.show', [$feed])
            ->with('flash', 'Your RSS feed has been updated.');
    }

    /**
     * Deletes a given feed.
     *
     * @param  \App\Feed $feed
     * @return \Illuminate\Http\Response
     */
    public function destroy(Feed $feed)
    {
        $this->authorize('update', $feed);

        $feed->delete();

        if (request()->wantsJson()) {
            return response([], 204);
        }

        return redirect()->route('feeds.index')
            ->with('flash', 'Your RSS feed has been deleted.');
    }
}
