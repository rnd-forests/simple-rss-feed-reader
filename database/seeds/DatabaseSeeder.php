<?php

use App\Feed;
use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = factory(User::class)->create([
            'name' => 'Vinh Nguyen',
            'email' => 'ngocvinh.nnv@gmail.com',
            'password' => bcrypt('12345678'),
        ]);

        $feeds = [
            ['title' => 'Laravel News', 'url' => 'https://feed.laravel-news.com'],
            ['title' => 'Viblo', 'url' => 'https://viblo.asia/rss'],
            ['title' => 'Ansible Blog', 'url' => 'https://www.ansible.com/blog/rss.xml'],
            ['title' => 'Laracasts', 'url' => 'https://laracasts.com/feed'],
            ['title' => 'Vue.js Feed', 'url' => 'https://vuejsfeed.com/feed'],
            ['title' => 'VNExpress Law', 'url' => 'https://vnexpress.net/rss/phap-luat.rss'],
        ];

        foreach ($feeds as $feed) {
            $user->feeds()->save(new Feed($feed));
        }
    }
}
