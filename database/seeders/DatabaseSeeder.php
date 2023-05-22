<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Report;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Report::factory(1_000_000)->create();

        $this->call([
            // UserSeeder::class,
            // UserFollowerSeeder::class,
            // PostSeeder::class,
            // PostCommentsSeeder::class,
            // PostReactionSeeder::class,
            // CommentReactionSeeder::class,
            // PostFileSeeder::class,
        ]);
    }
}
