<?php

/**
 * Views
 */

//test
use App\Models\Projects\Timer;

Route::get('/test', function()
{
    $timer = Timer::first();
    dd($timer->price);
});

//Homepage (entries)
Route::get('/', 'EntriesController@index');

//Units
Route::get('/units', 'Units\UnitsController@index');

//Foods
Route::get('/foods', 'Foods\FoodsController@index');

//Exercises
Route::get('/exercises', 'Exercises\ExercisesController@index');

//Journal
Route::get('/journal', 'Journal\JournalController@index');

//Timer
Route::get('projects', ['as' => 'projects', 'uses' => 'Projects\ProjectsController@index']);

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
 * Resources
 */

Route::resource('weights', 'Weights\WeightsController');
Route::resource('projects', 'Projects\ProjectsController', ['only' => ['index', 'store', 'destroy']]);
Route::resource('timers', 'Projects\TimersController', ['only' => ['destroy']]);
Route::resource('foods', 'Foods\FoodsController', ['only' => ['index', 'destroy']]);
Route::resource('exercises', 'Exercises\ExercisesController', ['only' => ['index', 'store', 'destroy']]);

//Route::resource('journal', 'Journal\JournalController', ['only' => ['index', 'store', 'destroy']]);

/**
 * Ajax
 */

/**
 * Projects
 */

Route::post('insert/startProjectTimer', 'Projects\TimersController@startProjectTimer');
Route::post('insert/payer', 'Projects\ProjectsController@addPayer');
Route::post('update/stopProjectTimer', 'Projects\TimersController@stopProjectTimer');

/**
 * Exercises
 */

//this should be ExercisesController, index method
Route::post('select/getExercises', 'Exercises\ExercisesController@getExercises');
//this should be ExercisesController, show method
Route::post('select/getExerciseInfo', 'Exercises\ExercisesController@getExerciseInfo');
//this should be ExerciseSeriesController, show method
Route::post('select/getExerciseSeriesInfo', 'Exercises\ExercisesController@getExerciseSeriesInfo');
//this should be ExerciseSeriesHistoryController, index (if returns multiple series, show if returns one series) method
Route::post('select/exerciseSeriesHistory', 'Exercises\ExercisesController@getExerciseSeriesHistory');
//this one is more complicated
Route::post('select/specificExerciseEntries', 'Exercises\ExerciseEntriesController@getSpecificExerciseEntries');
Route::post('insert/exercise', 'Exercises\ExercisesController@insertExercise');
Route::post('insert/deleteAndInsertSeriesIntoWorkouts', 'Exercises\ExercisesController@deleteAndInsertSeriesIntoWorkouts');
Route::post('insert/exerciseSet', 'Exercises\ExerciseEntriesController@insertExerciseSet');
Route::post('insert/exerciseEntry', 'Exercises\ExerciseEntriesController@insertExerciseEntry');
Route::post('insert/exerciseSeries', 'Exercises\ExerciseSeriesController@insertExerciseSeries');
Route::post('insert/workout', 'Exercises\WorkoutsController@insertWorkout');
Route::post('update/exerciseSeries', 'Exercises\ExercisesController@updateExerciseSeries');
Route::post('update/exerciseStepNumber', 'Exercises\ExercisesController@updateExerciseStepNumber');
Route::post('update/defaultExerciseQuantity', 'Exercises\ExercisesController@updateDefaultExerciseQuantity');
Route::post('update/defaultExerciseUnit', 'Exercises\ExercisesController@updateDefaultExerciseUnit');
Route::post('delete/exercise', 'Exercises\ExercisesController@deleteExercise');
Route::post('delete/exerciseEntry', 'Exercises\ExerciseEntriesController@deleteExerciseEntry');
Route::post('delete/exerciseSeries', 'Exercises\ExerciseSeriesController@deleteExerciseSeries');

/**
 * Foods
 */

Route::post('select/foodInfo', 'Foods\FoodsController@getFoodInfo');
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

Route::post('select/getJournalEntry', 'Journal\JournalController@getJournalEntry');
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