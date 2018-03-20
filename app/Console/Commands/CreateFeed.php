<?php

namespace App\Console\Commands;

use App\Feed;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class CreateFeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feed:make
        {user : The user associated with new feed}
        {title : The title of the feed}
        {url : The URL of the feed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new feed for a given user.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $userId = $this->argument('user');
        $user = User::find($userId);

        if (is_null($user)) {
            $this->error('Cannot find the given user.');
            return;
        }

        $attributes = [
            'title' => $this->argument('title'),
            'url' => $this->argument('url'),
        ];

        $validator = Validator::make($attributes, [
            'title' => 'bail|required|max:255',
            'url' => [
                'required',
                'active_url',
                Rule::unique('feeds')->where(function ($query) use ($user) {
                    return $query->where('user_id', $user->id);
                }),
            ]
        ]);

        if ($validator->passes()) {
            $feed = new Feed($attributes);
            $user->feeds()->save($feed);
            $this->info('Feed has been created.');

            return;
        }

        $this->error($validator->errors()->toJson());
    }
}
