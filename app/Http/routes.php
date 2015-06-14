<?php

/**
 * Views
 */

//test
use App\Models\Exercises\Exercise;
use App\Models\Journal\Journal;
use App\Models\Projects\Payer;
use App\Models\Projects\Project;
use App\Models\Projects\Timer;

//Route::get('/test', function()
//{
//    $payer = Payer::first();
//    dd($payer->owed);
//});

//Homepage (entries)
Route::get('/', 'EntriesController@index');

//Units
Route::get('/units', 'Units\UnitsController@index');

//Foods
Route::get('/foods', 'Foods\FoodsController@index');

//Exercises
Route::get('exercises', 'PagesController@exercises');

//Journal
Route::get('/journal', 'Journal\JournalController@index');

//Projects
Route::get('projects', ['as' => 'projects', 'uses' => 'Projects\ProjectsController@index']);
Route::get('payee', ['as' => 'payee', 'uses' => 'Projects\PayeeController@index']);
Route::get('payer', ['as' => 'payer', 'uses' => 'Projects\PayerController@index']);

//Credits
Route::get('/credits', function()
{
    return view('credits');
});

/**
 * Authentication
 */

Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function(){

    Route::group(['middleware' => 'guest'], function(){
        // Login
        Route::get('login', ['as' => 'auth.login', 'uses' => 'AuthController@getLogin']);
        Route::post('login', ['as' => 'auth.login.store', 'before' => 'throttle:2,60', 'uses' => 'AuthController@postLogin']);

        // Register
        Route::get('register', ['as' => 'auth.register', 'uses' => 'AuthController@getRegister']);
        Route::post('register', ['as' => 'auth.register.store', 'uses' => 'AuthController@postRegister']);
    });

    Route::group(['middleware' => 'auth'], function(){
        // Logout
        Route::get('logout', ['as' => 'auth.logout', 'uses' => 'AuthController@getLogout']);
    });

});

Route::controllers([
	// 'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

/**
 * Bindings
 */
Route::bind('journal', function($value)
{
    return Journal::getJournalEntry($value);
});
Route::bind('exercises', function($value)
{
    return Exercise::findOrFail($value);
});

/**
 * Resources
 */

Route::resource('weights', 'Weights\WeightsController');
Route::resource('projects', 'Projects\ProjectsController', ['only' => ['index', 'store', 'destroy']]);
Route::resource('payee', 'Projects\PayeeController', ['only' => ['index', 'store', 'destroy']]);
Route::resource('payer', 'Projects\PayerController', ['only' => ['index', 'store', 'destroy']]);
Route::resource('timers', 'Projects\TimersController', ['only' => ['destroy']]);
Route::resource('foods', 'Foods\FoodsController', ['only' => ['index', 'show', 'destroy']]);
Route::resource('exercises', 'Exercises\ExercisesController');
Route::resource('ExerciseEntries', 'Exercises\ExerciseEntriesController', ['only' => ['store']]);
Route::resource('ExerciseSeries', 'Exercises\ExerciseSeriesController', ['only' => ['store', 'show', 'destroy']]);
Route::resource('workouts', 'Exercises\WorkoutsController', ['only' => ['store']]);
Route::resource('journal', 'Journal\JournalController', ['only' => ['show', 'store']]);

/**
 * Ajax
 */

/**
 * Projects
 */

Route::post('insert/startProjectTimer', 'Projects\TimersController@startProjectTimer');
// @TODO Controller does not exists!
//Route::post('insert/payer', 'Projects\PayeesController@addPayerToPayee');
Route::post('update/stopProjectTimer', 'Projects\TimersController@stopProjectTimer');

/**
 * Exercises
 */

Route::post('insert/exerciseSet', 'Exercises\ExerciseEntriesController@insertExerciseSet');
Route::post('delete/exerciseEntry', 'Exercises\ExerciseEntriesController@deleteExerciseEntry');
//this one is more complicated
Route::post('select/specificExerciseEntries', 'Exercises\ExerciseEntriesController@getSpecificExerciseEntries');
Route::post('update/defaultExerciseQuantity', 'Exercises\ExercisesController@updateDefaultExerciseQuantity');
Route::post('update/defaultExerciseUnit', 'Exercises\ExercisesController@updateDefaultExerciseUnit');
Route::post('update/exerciseSeries', 'Exercises\ExercisesController@updateExerciseSeries');
//this should be ExerciseSeriesHistoryController, index (if returns multiple series, show if returns one series) method
Route::post('select/exerciseSeriesHistory', 'Exercises\ExercisesController@getExerciseSeriesHistory');
Route::post('insert/deleteAndInsertSeriesIntoWorkouts', 'Exercises\ExercisesController@deleteAndInsertSeriesIntoWorkouts');

/**
 * Foods
 */

Route::post('select/allFoodsWithUnits', 'Foods\FoodsController@getAllFoodsWithUnits');
Route::post('insert/menuEntry', 'Foods\FoodEntriesController@insertMenuEntry');
Route::post('insert/food', 'Foods\FoodsController@insertFood');
Route::post('insert/unitInCalories', 'Foods\FoodsController@insertUnitInCalories');
Route::post('delete/unitFromCalories', 'Foods\FoodsController@deleteUnitFromCalories');
Route::post('delete/foodEntry', 'Foods\FoodEntriesController@deleteFoodEntry');
Route::post('update/defaultUnit', 'Foods\FoodsController@updateDefaultUnit');
Route::post('update/calories', 'Foods\FoodsController@updateCalories');

/**
 * Journal
 */

Route::post('insert/journalEntry', 'Journal\JournalController@insertOrUpdateJournalEntry');

/**
 * Recipes
 */

Route::post('select/filterRecipes', 'Recipes\RecipesController@filterRecipes');
Route::post('select/recipeContents', 'Recipes\RecipesController@getRecipeContents');
Route::post('insert/quickRecipe', 'Recipes\QuickRecipesController@quickRecipe');
Route::post('insert/recipeMethod', 'Recipes\RecipesController@insertRecipeMethod');
Route::post('insert/recipe', 'Recipes\RecipesController@insertRecipe');
Route::post('insert/recipeEntry', 'Recipes\RecipesController@insertRecipeEntry');
Route::post('insert/foodIntoRecipe', 'Recipes\RecipesController@insertFoodIntoRecipe');
Route::post('delete/recipe', 'Recipes\RecipesController@deleteRecipe');
Route::post('delete/foodFromRecipe', 'Recipes\RecipesController@deleteFoodFromRecipe');
Route::post('delete/recipeEntry', 'Recipes\RecipesController@deleteRecipeEntry');
Route::post('update/recipeMethod', 'Recipes\RecipesController@updateRecipeMethod');

/**
 * Tags
 */

Route::post('insert/tagsIntoRecipe', 'Recipes\RecipesController@insertTagsIntoRecipe');
Route::post('insert/recipeTag', 'Tags\TagsController@insertRecipeTag');
Route::post('insert/tagInExercise', 'Tags\TagsController@insertTagInExercise');
Route::post('insert/exerciseTag', 'Tags\TagsController@insertExerciseTag');
Route::post('insert/tagsInExercise', 'Tags\TagsController@insertTagsInExercise');
Route::post('delete/exerciseTag', 'Tags\TagsController@deleteExerciseTag');
Route::post('delete/recipeTag', 'Tags\TagsController@deleteRecipeTag');
Route::post('delete/tagFromExercise', 'Tags\TagsController@deleteTagFromExercise');

/**
 * Units
 */

Route::post('insert/exerciseUnit', 'Units\UnitsController@insertExerciseUnit');
Route::post('insert/foodUnit', 'Units\UnitsController@insertFoodUnit');
Route::post('delete/foodUnit', 'Units\UnitsController@deleteFoodUnit');
Route::post('delete/exerciseUnit', 'Units\UnitsController@deleteExerciseUnit');

/**
 * Entries
 */

Route::post('select/entries', 'EntriesController@getEntries');

/**
 * Weight
 */

Route::post('insert/weight', 'Weights\WeightsController@insertOrUpdateWeight');

/**
 * Autocomplete
 */

//This selects rows from both foods and recipes table.
Route::post('select/autocompleteMenu', 'Search\AutocompleteController@autocompleteMenu');
Route::post('select/autocompleteExercise', 'Search\AutocompleteController@autocompleteExercise');
Route::post('select/autocompleteFood', 'Search\AutocompleteController@autocompleteFood');