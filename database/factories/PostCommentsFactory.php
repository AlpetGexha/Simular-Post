<?php

namespace Database\Factories;

use App\Models\PostComments;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PostComments>
 */
class PostCommentsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        //        $posts = Post::all('id');
        //        $users = User::all('id');

        return [
            //            'post_id'      => random_int(1, 10000),
            'user_id' => random_int(1, 1000),
            'comment_text' => $this->faker->paragraphs(rand(1, 5), true),
        ];
    }

    public function configure(): PostCommentsFactory
    {
        return $this->afterCreating(function (PostComments $comment) {
            if ($this->faker->boolean(35)) {
                $comments = PostComments::where('id', '!=', $comment->id)->inRandomOrder()->first();

                $comment->update([
                    'parent_comment_id' => $comments->id,
                ]);
            }
        });
    }
}
