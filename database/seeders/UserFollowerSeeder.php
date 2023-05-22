<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserFollowerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::chunk(100, function ($users) {
            foreach ($users as $user) {
                $randomUsers = User::query()
                    ->inRandomOrder()
                    ->whereNot('id', $user->id)
                    ->take(rand(10, 100))
                    ->get();

                $randomUsers->each->follow($user);
            }
        });
    }
}
