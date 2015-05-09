<?php namespace App\Services\Autocomplete;

use DB;
use App\Models\Foods\Food;
use App\Models\Foods\Recipe;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class FoodsAndRecipesAutocomplete
 * @package App\Services\Autocomplete
 * @author  Valentin PRUGNAUD <valentin@whatdafox.com>
 * @url http://www.foxted.com
 */
class FoodsAndRecipesAutocomplete implements Autocomplete
{

    protected $results;

    public function __construct()
    {
        $this->results = new Collection;
    }

    /**
     * Perform a search given a specific query
     * @param $query
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function search($query)
    {
        // Now here you can define you search method, using the food and the recipe model or Laravel's QueryBuilder
        // if your query is really really really (I insist!) complex
        $query = '%' . $query . '%';

        // Fill the $this->results property with the food items
        $this->searchFoodItems($query);

        // Fill the $this->results property with the recipe items
        $this->searchRecipeItems($query);

        // Return the results
        return $this->results;

    }

    /**
     * Search only food items
     * @param $query
     * @return void
     */
    protected function searchFoodItems($query)
    {
        // Query the database
        $foodItems = Food::select('id', 'name', DB::raw("'food' as type"))
                   ->where('name', 'LIKE', $query)
                   ->forCurrentUser()
                   ->get();

        // Add each element to the $this->results collection
        $foodItems->each(function ($item){
            $this->results->add($item);
        });
    }

    /**
     * Search only recipe items
     * @param $query
     * @return void
     */
    protected function searchRecipeItems($query)
    {
        // Query the database
        $recipeItems = Recipe::select('id', 'name', DB::raw("'recipe' as type"))
                     ->where('name', 'LIKE', $query)
                     ->forCurrentUser()
                     ->get();

        // Add each element to the $this->results collection
        $recipeItems->each(function ($item) {
            $this->results->add($item);
        });
    }

}