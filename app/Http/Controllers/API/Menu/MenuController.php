<?php

namespace App\Http\Controllers\API\Menu;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Transformers\Menu\FoodTransformer;
use App\Http\Transformers\Menu\RecipeTransformer;
use App\Models\Menu\Food;
use App\Models\Menu\Recipe;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MenuController extends Controller
{
    /**
     * For autocompleting.
     * Select rows from both foods and recipes table.
     *
     * @VP: I feel like I should transform the foods and recipes here,
     * to be consistent, but then there would be so much stuff I don't need.
     * (For example, I only need the name and id for the recipes.)
     *
     * So I did use transformers, but it's a bit of a mess,
     * and I suppose there's a better way to write this code,
     * since I'm adding the
     * data attribute at the end just so it's consistent with
     * my other autocomplete responses.
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $typing = '%' . $request->get('typing') . '%';
        $foods = $this->foods($typing);
        $recipes = $this->recipes($typing)->toArray();

        $foods = $this->transform($this->createCollection($foods, new FoodTransformer), ['units'])['data'];

        //Specify whether the menu item is a food or recipe
        foreach ($foods as $index => $food) {
            $foods[$index]['type'] = 'food';
        }

        $menu = $foods;
        foreach ($recipes as $recipe) {
            $recipe['type'] = 'recipe';
            $menu[] = $recipe;
        }

        //Sort by name and change the array indexes so they are ordered correctly, too
        //(Having the indexes ordered correctly makes it easier to test the ordering is correct)
        usort($menu, function($a, $b) {
            return strcmp($a["name"], $b["name"]);
        });

        //So that for populating the autocomplete, there is a
        //data attribute like the food and exercise autocomplete responses
        $response = [
            'data' => $menu
        ];

        return response($response, Response::HTTP_OK);
    }

    /**
     *
     * @param $typing
     * @return mixed
     */
    private function foods($typing)
    {
        $foods = Food::where('user_id', Auth::user()->id)
            ->where('name', 'LIKE', $typing)
            ->with('defaultUnit')
            ->with('units')
            ->get();

        return $foods;
    }

    /**
     *
     * @param $typing
     * @return mixed
     */
    private function recipes($typing)
    {
        $recipes = Recipe::where('user_id', Auth::user()->id)
            ->where('name', 'LIKE', $typing)
            ->select('id', 'name')
            ->get();

        return $recipes;
    }

    /**
     * Valentin's code
     */

    /**
     * Selects rows from both foods and recipes table for autocomplete
     * @param Request $request
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
