<?php namespace App\Http\Controllers\Search;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\Autocomplete\FoodsAndRecipesAutocomplete;
use Illuminate\Http\Request;
use DB;
use Auth;

/**
 * Models
 */
use App\Models\Foods\Food;
use App\Models\Foods\Recipe;
use App\Models\Exercises\Exercise;

class AutocompleteController extends Controller {

    //Selects rows from both foods and recipes table.
    public function autocompleteMenu(Request $request)
    {
        $typing = '%' . $request->get('typing') . '%';
        $foods = $this->foods($typing);
        $recipes = $this->recipes($typing);

        //Specify whether the menu item is a food or recipe
        foreach ($foods as $food) {
            $food->type = 'food';
        }
        foreach ($recipes as $recipe) {
            $recipe->type = 'recipe';
        }
        
        $menu = $foods->merge($recipes);
        $menu = $menu->sortBy(function($item)
        {
            return $item->name;
        })->reverse()->reverse();

       // $menu = new Illuminate\Support\Collection([])
               // $menu = collect([$food->toArray(), $recipe->toArray()]);
               
               // dd($menu->collapse()->toArray());
               
               // $menu->reverse()->reverse()->sortBy(function($item)
               //  {
               //      return $item->name;
               //  });
               
               // $menu = new Collection();
               // $menu->add();


        /**
         * @VP:
         * Why won't this sort by name in ascending order?
         */
        // $menu = $foods->merge($recipes)
        //     ->sortBy(function($item)
        //     {
        //         var_dump($item->name);
        //         return $item->name;
        //     });
        //     dd();
        // $menu = $foods->merge($recipes)
        //     ->sortBy('name');

        // return [
        //     'foods' => $foods,
        //     'recipes' => $recipes
        // ];
        
        // $menu = DB::select("select * from (select id, name, 'food' as type from foods where name LIKE '$typing' and user_id = " . Auth::user()->id . " UNION select id, name, 'recipe' as type from recipes where name LIKE '$typing' and user_id = " . Auth::user()->id . ") as table1 order by table1.name asc");

        return $menu;
    }

    public function autocompleteFood(Request $request)
    {
        $typing = '%' . $request->get('typing') . '%';
        return $this->foods($typing);
    }

    private function foods($typing)
    {
        $foods = Food::where('user_id', Auth::user()->id)
            ->where('name', 'LIKE', $typing)
            ->with('defaultUnit')
            ->with('units')
            ->get();
           
        return $foods;
    }

    private function recipes($typing)
    {
        $recipes = Recipe::where('user_id', Auth::user()->id)
            ->where('name', 'LIKE', $typing)
            ->get();

        return $recipes;
    }

    public function autocompleteExercise(Request $request)
    {
        $exercise = '%' . $request->get('exercise') . '%';
    
        $exercises = Exercise
            ::where('name', 'LIKE', $exercise)
            ->where('user_id', Auth::user()->id)
            ->select('id', 'name', 'description', 'default_unit_id', 'default_quantity')
            ->get();

        return $exercises;
    }



    /**
     * Valentin's code
     */

    /**
     * Selects rows from both foods and recipes table for autocomplete
     * @param Request                   $request
     * @param FoodsAndRecipesAutocomplete $autocomplete
     * @return \Illuminate\Database\Eloquent\Collection
     */
    
    public function foodsAndRecipes(Request $request, FoodsAndRecipesAutocomplete $autocomplete)
    {
        // You can just use the Request object here and get rid of these two lines
        // include(app_path() . '/inc/functions.php');
        // $typing = json_decode(file_get_contents('php://input'), true)["typing"];

        // For this part, since you are doing some "Autocomplete Search" on multiple models, the best approach would
        // be to create a autocomplete search service, you can use the app/Services directory for that.
        // To use a given service, just typehint it as a parameter to the method and Laravel will build it for you
        return $autocomplete->search($request->get('typing'));
    }

}
