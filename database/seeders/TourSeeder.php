<?php

namespace Database\Seeders;

use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
		// Get all travel records
		$travels = Travel::all();
	
		// Create tours associated with each travel
		$travels->each(function ($travel) {
			// Create 3 tours for each travel
			$tours = Tour::factory()->count(3)->create([
				'travel_id' => $travel->id,
			]);
		});
    }
}
