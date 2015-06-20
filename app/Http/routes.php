<?php

use App\Models\Exercises\Exercise;
use App\Models\Journal\Journal;
use App\Models\Projects\Payer;
use App\Models\Projects\Project;
use App\Models\Projects\Timer;

/**
 * Views
 */

//test
Route::get('/test', function()
{
    $project = Project::first();
    return $project;
});

//Homepage (entries)
Route::get('/', 'PagesController@entries');

//Units
Route::get('/units', 'PagesController@units');

//Foods
Route::get('/foods', 'PagesController@foods');

//Exercises
Route::get('exercises', 'PagesController@exercises');

//Journal
Route::get('/journal', 'PagesController@journal');

//Projects
//Route::get('projects', ['as' => 'projects', 'uses' => 'PagesController@index']);
Route::get('payee', ['as' => 'payee', 'uses' => 'PagesController@payee']);
Route::get('payer', ['as' => 'payer', 'uses' => 'PagesController@payer']);

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

//Route::bind('journal', function($date)
//{
//    return Journal::where('date', $date);
//});

Route::bind('exercises', function($value)
{
    return Exercise::findOrFail($value);
});

/**
 * Resources
 */

Route::resource('weights', 'Weights\WeightsController');
Route::resource('projects', 'Projects\ProjectsController', ['only' => ['show', 'store', 'destroy']]);
Route::resource('payee', 'Projects\PayeeController', ['only' => ['store', 'destroy']]);
Route::resource('payer', 'Projects\PayerController', ['only' => ['store', 'destroy']]);
Route::resource('timers', 'Projects\TimersController', ['only' => ['destroy']]);
Route::resource('foods', 'Foods\FoodsController', ['only' => ['show', 'destroy']]);
Route::resource('exercises', 'Exercises\ExercisesController', ['except' => ['index']]);
Route::resource('ExerciseEntries', 'Exercises\ExerciseEntriesController', ['only' => ['store']]);
Route::resource('ExerciseSeries', 'Exercises\ExerciseSeriesController', ['only' => ['store', 'show', 'destroy']]);
Route::resource('workouts', 'Exercises\WorkoutsController', ['only' => ['store']]);
Route::resource('journal', 'Journal\JournalController', ['only' => ['show', 'store', 'update']]);

/**
 * Ajax
 */

/**
 * Projects
 */

Route::post('insert/startProjectTimer', 'Projects\TimersController@startProjectTimer');
Route::post('insert/payer', 'Projects\PayeeController@addPayer');
Route::post('delete/payer', 'Projects\PayeeController@removePayer');
Route::post('update/markAsPaid', 'Projects\TimersController@markAsPaid');
Route::post('update/stopProjectTimer', 'Projects\TimersController@stopProjectTimer');

/**
 * Exercises
 */

Route::post('insert/exerciseSet', 'Exercises\ExerciseEntriesController@insertExerciseSet');
Route::post('delete/exerciseEntry', 'Exercises\ExerciseEntriesController@deleteExerciseEntry');
//this one is more complicated
Route::post('select/specificExerciseEntries', 'Exercises\ExerciseEntriesController@getSpecificExerciseEntries');
//this should be ExerciseSeriesHistoryController, index (if returns multiple series, show if returns one series) method
Route::post('select/exerciseSeriesHistory', 'Exercises\ExercisesController@getExerciseSeriesHistory');
Route::post('insert/deleteAndInsertSeriesIntoWorkouts', 'Exercises\ExerciseSeriesController@deleteAndInsertSeriesIntoWorkouts');
Route::post('insert/tagsInExercise', 'Tags\TagsController@insertTagsInExercise');

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