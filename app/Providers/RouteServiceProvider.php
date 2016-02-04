<?php namespace App\Providers;

use App\Models\Exercises\Exercise;
use App\Models\Exercises\Series as ExerciseSeries;
use App\Models\Menu\Food;
use App\Models\Menu\Entry as MenuEntry;
use App\Models\Menu\Recipe;
use App\Models\Journal\Journal;
use App\Models\Tags\Tag;
use App\Models\Timers\Activity;
use App\Models\Timers\Timer;
use App\Models\Units\Unit;
use App\Models\Weights\Weight;
use Carbon\Carbon;
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

        Route::bind('exerciseTags', function($id)
        {
            return Tag::forCurrentUser()
                ->where('for', 'exercise')
                ->findOrFail($id);
        });

        Route::bind('recipeTags', function($id)
        {
            return Tag::forCurrentUser()
                ->where('for', 'recipe')
                ->findOrFail($id);
        });

        Route::bind('foodUnits', function($id)
        {
            return Unit::forCurrentUser()->where('for', 'food')->findOrFail($id);
        });

        Route::bind('exerciseUnits', function($id)
        {
            return Unit::forCurrentUser()->where('for', 'exercise')->findOrFail($id);
        });

        Route::bind('timers', function($id)
        {
            return Timer::forCurrentUser()->findOrFail($id);
        });

        Route::bind('activities', function($id)
        {
            return Activity::forCurrentUser()->findOrFail($id);
        });

        Route::bind('weights', function($id)
        {
            return Weight::forCurrentUser()->findOrFail($id);
        });

        /**
         * $parameter is either the id or the date
         */
        Route::bind('journal', function($parameter)
        {
            /**
             * @VP:
             * Is there a better way to check if the $parameter is an
             * id or a date? When I tried using Carbon to create an object from
             * the parameter, it threw an exception when the $parameter was the id,
             * whereas I just wanted a boolean.
             */
            if (strrpos($parameter, '-')) {
                //$parameter is the date of the entry
                $journal = Journal::forCurrentUser()
                    ->where('date', $parameter)
                    ->first();
            }
            else {
                //$parameter is the id of the entry
                $journal = Journal::forCurrentUser()->findOrFail($parameter);
            }
            return $journal;
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
