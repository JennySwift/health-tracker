<?php

use App\Models\Menu\Entry as FoodEntry;
use App\Models\Menu\Entry;
use App\Models\Menu\Food;
use App\Models\Menu\Recipe;
use App\Models\Units\Unit;
use App\User;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

/**
 * Class FoodEntrySeeder
 */
class FoodEntrySeeder extends Seeder
{
    private $user;

    /**
     *
     */
    public function run()
    {
        FoodEntry::truncate();

        foreach (User::all() as $user) {
            $this->user = $user;

            //Create menu entries for the last 5 days
            foreach (range(0, 4) as $index) {
                $date = Carbon::today()->subDays($index)->format('Y-m-d');
                $this->createEntriesForOneDay($date, $index + 5);
            }
        }


    }

    /**
     *
     * @param $date
     */
    private function createEntriesForOneDay($date, $quantity)
    {
        $faker = Faker::create();

        //Create 2 entries for the day
        foreach (range(0, 1) as $index) {
            $entry = new Entry([
                'date' => $date,
                'quantity' => $quantity,
                'recipe_id' => '',
            ]);

            $entry->user()->associate($this->user);

            //Attach food
            $food_ids = Food::where('user_id', $this->user->id)->lists('id')->all();
            $entry->food()->associate(Food::find($food_ids[$index]));

            //Attach unit
            $unit_ids = collect($entry->food->units)->lists('id');
            $entry->unit()->associate(Unit::find($unit_ids[$index]));

            //Attach recipe for the last entry if the date is today
            if ($date === Carbon::today()->format('Y-m-d') && $index === 1) {
                $recipe_ids = Recipe::where('user_id', $this->user->id)->lists('id')->all();
//                dd($recipe_ids);
                $entry->recipe()->associate(Recipe::find($recipe_ids[0]));
            }



            $entry->save();

        }
    }

}