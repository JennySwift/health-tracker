<?php namespace App\Http\Controllers\Search;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Services\Autocomplete\FoodsAndRecipesAutocomplete;
use Illuminate\Http\Request;

/**
 * Class AutocompleteController
 * @package App\Http\Controllers\Search
 */
class AutocompleteController extends Controller {

    /**
     * Selects rows from both foods and recipes table for autocomplete
     * @param Request                   $request
     * @param FoodsAndRecipesAutocomplete $autocomplete
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function foodsAndRecipes(Request $request, FoodsAndRecipesAutocomplete $autocomplete)
    {
        // You can just use the Request object here and get rid of these two lines
//		include(app_path() . '/inc/functions.php');
//		$typing = json_decode(file_get_contents('php://input'), true)["typing"];

        // For this part, since you are doing some "Autocomplete Search" on multiple models, the best approach would
        // be to create a autocomplete search service, you can use the app/Services directory for that.
        // To use a given service, just typehint it as a parameter to the method and Laravel will build it for you
        return $autocomplete->search($request->get('typing'));
    }

}
