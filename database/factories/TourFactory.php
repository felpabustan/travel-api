<?php

namespace Database\Factories;

use App\Models\Tour;
use App\Models\Travel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tour>
 */
class TourFactory extends Factory
{
	protected $model = Tour::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
		// Get a random travel ID from the Travel model
		$travel_id = Travel::pluck('id')->random();
	
		// We randomize the starting date picking from random dates of the month of june and the end date is min 4 days - max 6 days from the start date
		$startingDate = Carbon::parse('2023-06-01')->addDays(rand(1, 29));
		$endingDate = $startingDate->copy()->addDays(rand(4, 6))->endOfDay();
	
		return [
			'travel_id' => $travel_id,
			'name' => $this->faker->sentence,
			'starting_date' => $startingDate->format('Y-m-d'),
			'ending_date' => $endingDate->format('Y-m-d'),
			'price' => $this->faker->randomFloat(2, 10, 999),
		];
    }
}
