<?php

namespace Database\Factories;

use App\Enum\ReportEnum;
use App\Models\Post;
use App\Models\PostComments;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $reportable = $this->reportable();

        return [
            'reportable_id' => $reportable::factory(),
            'reportable_type' => $reportable,
            'status' => $this->faker->randomElements(ReportEnum::cases()),
        ];
    }

    public function reportable()
    {
        return $this->faker->randomElement([
            Post::class,
            User::class,
            PostComments::class,
        ]);
    }
}
