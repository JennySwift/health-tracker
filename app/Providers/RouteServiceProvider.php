<?php namespace App\Providers;

use App\Models\Exercises\Exercise;
use App\Models\Exercises\Series as ExerciseSeries;
use App\Models\Foods\Food;
use App\Models\Foods\Entry as MenuEntry;
use App\Models\Foods\Recipe;
use App\Models\Journal\Journal;
use App\Models\Tags\Tag;
use App\Models\Units\Unit;
use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use App\Models\Exercises\Entry as ExerciseEntry;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider {

	/**
	 * This namespace is applied to the controller routes in your routes file.
	 *
	 * In addition, it is set as the URL generator's root namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'App\Http\Controllers';

	/**
	 * Define your route model bindings, pattern filters, etc.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function boot(Router $router)
	{
		parent::boot($router);

        Route::bind('exercises', function($id)
        {
            return Exercise::forCurrentUser()->findOrFail($id);
        });

        Route::bind('exerciseEntries', function($id)
        {
            return ExerciseEntry::forCurrentUser()->findOrFail($id);
        });

        Route::bind('menuEntries', function($id)
        {
            return MenuEntry::forCurrentUser()->findOrFail($id);
        });

        Route::bind('exerciseSeries', function($id)
        {
            return ExerciseSeries::forCurrentUser()->findOrFail($id);
        });

        Route::bind('foods', function($id)
        {
            return Food::forCurrentUser()->findOrFail($id);
        });

        Route::bind('recipes', function($id)
        {
            return Recipe::forCurrentUser()->findOrFail($id);
        });

        Route::bind('tags', function($id)
        {
            return Tag::forCurrentUser()->findOrFail($id);
        });

        Route::bind('foodUnits', function($id)
        {
            return Unit::forCurrentUser()->where('for', 'food')->findOrFail($id);
        });

        Route::bind('exerciseUnits', function($id)
        {
            return Unit::forCurrentUser()->where('for', 'exercise')->findOrFail($id);
        });

        Route::bind('journal', function($id)
        {
            return Journal::forCurrentUser()->findOrFail($id);
        });
	}

	/**
	 * Define the routes for the application.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function map(Router $router)
	{
		$router->group(['namespace' => $this->namespace], function($router)
		{
			require app_path('Http/routes.php');
		});
	}

}
