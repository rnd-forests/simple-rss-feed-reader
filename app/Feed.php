<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Feed extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'url', 'slug'];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = ['path'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($feed) {
            Cache::tags(config('rss.cache_tag_key'))->forget($feed->getCacheKey());
        });

        static::created(function ($feed) {
            $feed->update(['slug' => $feed->title]);
        });
    }

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
     * Fetches the path to the feed as a property.
     *
     * @return string
     */
    public function getPathAttribute()
    {
        return $this->path();
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

    /**
     * Sets the proper slug attribute.
     *
     * @param string $value
     */
    public function setSlugAttribute($value)
    {
        if (static::whereSlug($slug = str_slug($value))->exists()) {
            $slug = "{$slug}-{$this->id}";
        }

        $this->attributes['slug'] = $slug;
    }

    /**
     * Gets the route key name.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
