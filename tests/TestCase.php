<?php

use App\User;
use Carbon\Carbon;

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
        $this->assertArrayHasKey('unitIds', $food);
    }

    /**
     * Because a newly created food doesn't yet have a default unit
     * @param $food
     */
    protected function checkFoodKeysExistWithoutDefaultUnit($food)
    {
        $this->assertArrayHasKey('id', $food);
        $this->assertArrayHasKey('name', $food);
        $this->assertArrayHasKey('path', $food);
        $this->assertArrayHasKey('defaultCalories', $food);
    }

    /**
     *
     * @param $unit
     */
    protected function checkFoodUnitKeysExist($unit)
    {
        $this->assertArrayHasKey('id', $unit);
        $this->assertArrayHasKey('name', $unit);
        $this->assertArrayNotHasKey('created_at', $unit);
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
     * @param $weight
     */
    protected function checkWeightKeysExist($weight)
    {
        $this->assertArrayHasKey('id', $weight);
        $this->assertArrayHasKey('date', $weight);
        $this->assertArrayHasKey('weight', $weight);
    }

    /**
     *
     * @param $entry
     */
    protected function checkFoodEntryKeysExist($entry)
    {
        $this->assertArrayHasKey('id', $entry);
        $this->assertArrayHasKey('date', $entry);
        $this->assertArrayHasKey('quantity', $entry);
        $this->assertArrayHasKey('calories', $entry);
        $this->assertArrayHasKey('food', $entry);
        $this->assertArrayHasKey('unit', $entry);
    }

    /**
     *
     * @param $entry
     */
    protected function checkRecipeEntryKeysExist($entry)
    {
        $this->checkFoodEntryKeysExist($entry);
        $this->assertArrayHasKey('recipe', $entry);
    }

    /**
     *
     * @param $ingredient
     */
    protected function checkIngredientKeysExist($ingredient)
    {
        $this->assertArrayHasKey('food', $ingredient);
        $this->assertArrayHasKey('id', $ingredient['food']['data']);
        $this->assertArrayHasKey('name', $ingredient['food']['data']);
        $this->assertArrayHasKey('unit', $ingredient);
        $this->assertArrayHasKey('id', $ingredient['unit']['data']);
        $this->assertArrayHasKey('name', $ingredient['unit']['data']);
        $this->assertArrayHasKey('quantity', $ingredient);
        $this->assertArrayHasKey('description', $ingredient);
        $this->assertArrayHasKey('units', $ingredient['food']['data']);
    }

    /**
     *
     * @param $timer
     */
    protected function checkTimerKeysExist($timer)
    {
        $this->assertArrayHasKey('id', $timer);
        $this->assertArrayHasKey('start', $timer);
        $this->assertArrayHasKey('startDate', $timer);
        $this->assertArrayHasKey('hours', $timer);
        $this->assertArrayHasKey('minutes', $timer);
        $this->assertArrayHasKey('activity', $timer);
        $this->assertArrayHasKey('durationInMinutesForDay', $timer);
    }


}
