<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'url'];

    /**
     * A feed belongs to a specific user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Gets the URL to a given feed.
     *
     * @return string
     */
    public function path()
    {
        return route('feeds.show', ['feed' => $this]);
    }

    /**
     * Gets cache key for a given feed.
     *
     * @return string
     */
    public function getCacheKey()
    {
        return sprintf("feed-%d", $this->id);
    }
}
