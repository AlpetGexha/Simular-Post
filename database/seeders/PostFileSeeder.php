<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostFileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = 'app/post.jpg';

        file_put_contents(storage_path($path), file_get_contents(User::AVATAR_ULR));

        $posts = Post::inRandomOrder()->take(1000)->get();

        foreach ($posts as $post) {
            $post
                ->addMedia(storage_path($path))
                ->preservingOriginal()
                ->toMediaCollection('posts');
        }
    }
}
