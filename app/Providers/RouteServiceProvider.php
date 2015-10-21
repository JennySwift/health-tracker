<?php namespace App\Providers;

use App\Models\Exercises\Exercise;
use App\Models\Exercises\Series as ExerciseSeries;
use App\Models\Foods\Food;
use App\Models\Foods\Recipe;
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
