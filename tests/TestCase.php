<?php

use App\User;

class TestCase extends Illuminate\Foundation\Testing\TestCase {

    protected $baseUrl = 'http://localhost';

    protected $user;

	/**
	 * Creates the application.
	 *
	 * @return \Illuminate\Foundation\Application
	 */
	public function createApplication()
	{
		$app = require __DIR__.'/../bootstrap/app.php';

		$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

		return $app;
	}

    /**
     * Make an API call
     * @param $method
     * @param $uri
     * @param array $parameters
     * @param array $cookies
     * @param array $files
     * @param array $server
     * @param null $content
     * @return \Illuminate\Http\Response
     */
    public function apiCall($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        $headers = $this->transformHeadersToServerVars([
            'Accept' => 'application/json'
        ]);
        $server = array_merge($server, $headers);

        return parent::call($method, $uri, $parameters, $cookies, $files, $server, $content);
    }

    /**
     *
     * @return mixed
     */
    public function logInUser($id = 1)
    {
        $user = User::find($id);
        $this->be($user);
        $this->user = $user;
    }

    /**
     *
     * @param $food
     */
    protected function checkFoodKeysExist($food)
    {
        $this->assertArrayHasKey('id', $food);
        $this->assertArrayHasKey('name', $food);
        $this->assertArrayHasKey('path', $food);
        $this->assertArrayHasKey('defaultCalories', $food);
        $this->assertArrayHasKey('defaultUnit', $food);
    }

    /**
     *
     * @param $exercise
     */
    protected function checkExerciseKeysExist($exercise)
    {
        $this->assertArrayHasKey('id', $exercise);
        $this->assertArrayHasKey('name', $exercise);
        $this->assertArrayHasKey('stepNumber', $exercise);
        $this->assertArrayHasKey('series', $exercise);
        $this->assertArrayHasKey('defaultUnit', $exercise);
        $this->assertArrayHasKey('defaultQuantity', $exercise);
        $this->assertArrayHasKey('tag_ids', $exercise);
        $this->assertArrayHasKey('tags', $exercise);
        $this->assertArrayHasKey('program', $exercise);
        $this->assertArrayHasKey('lastDone', $exercise);
        $this->assertArrayHasKey('priority', $exercise);
        $this->assertArrayHasKey('target', $exercise);
    }

    /**
     *
     * @param $tag
     */
    protected function checkTagKeysExist($tag)
    {
        $this->assertArrayHasKey('id', $tag);
        $this->assertArrayHasKey('name', $tag);
    }

    /**
     *
     * @param $recipe
     */
    protected function checkRecipeKeysExist($recipe)
    {
        $this->assertArrayHasKey('id', $recipe);
        $this->assertArrayHasKey('name', $recipe);
        $this->assertArrayHasKey('steps', $recipe);
        $this->assertArrayHasKey('tag_ids', $recipe);
        $this->assertArrayHasKey('ingredients', $recipe);
        $this->assertArrayHasKey('tags', $recipe);
    }

    /**
     *
     * @param $ingredient
     */
    protected function checkIngredientKeysExist($ingredient)
    {
        $this->assertArrayHasKey('food_id', $ingredient);
        $this->assertArrayHasKey('name', $ingredient);
        $this->assertArrayHasKey('unit_name', $ingredient);
        $this->assertArrayHasKey('unit_id', $ingredient);
        $this->assertArrayHasKey('quantity', $ingredient);
        $this->assertArrayHasKey('description', $ingredient);
        $this->assertArrayHasKey('units', $ingredient);
    }


}
