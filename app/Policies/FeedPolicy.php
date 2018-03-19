<?php

namespace App\Policies;

use App\Feed;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FeedPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the feed.
     *
     * @param  User $user
     * @param  Feed $feed
     * @return mixed
     */
    public function view(User $user, Feed $feed)
    {
        return $feed->user_id === $user->id;
    }

    /**
     * Determine whether the user can update the feed.
     *
     * @param  User $user
     * @param  Feed $feed
     * @return mixed
     */
    public function update(User $user, Feed $feed)
    {
        return $feed->user_id === $user->id;
    }
}
