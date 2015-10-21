<?php

use Illuminate\Http\Response;

class PagesTest extends TestCase {

    /**
     * @test
     * @return void
     */
    public function it_redirects_the_user_if_not_authenticated()
    {
        $response = $this->call('GET', '/');

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertTrue($response->isRedirection());
        $this->assertRedirectedTo($this->baseUrl.'/auth/login');
    }

    /**
     * @test
     */
    public function it_tests_the_page_load_speed_of_home_page()
    {
        $start = microtime(true);
        $this->logInUser(2);
        $this->visit('/');
        $time = microtime(true) - $start;
        $this->assertLessThan(1, $time);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_display_the_homepage()
    {
        $this->logInUser();

        $this->visit('/')
            ->see($this->user->name)
            ->see('today');

        $this->assertEquals(Response::HTTP_OK, $this->apiCall('GET', '/')->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_display_the_foods_page()
    {
        $this->logInUser();

        $this->visit('/foods')
            ->see('banana');

        $this->assertEquals(Response::HTTP_OK, $this->apiCall('GET', '/')->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_display_the_recipes_page()
    {
        $this->logInUser();

        $this->visit('/recipes')
            ->see('soup');

        $this->assertEquals(Response::HTTP_OK, $this->apiCall('GET', '/')->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_display_the_food_units_page()
    {
        $this->logInUser();

        $this->visit('/food-units')
            ->see('large');

        $this->assertEquals(Response::HTTP_OK, $this->apiCall('GET', '/')->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_display_the_exercises_page()
    {
        $this->logInUser();

        $this->visit('/exercises')
            ->see('pullups');

        $this->assertEquals(Response::HTTP_OK, $this->apiCall('GET', '/')->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_display_the_exercise_series_page()
    {
        $this->logInUser();

        $this->visit('/series')
            ->see('pushup');

        $this->assertEquals(Response::HTTP_OK, $this->apiCall('GET', '/')->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_display_the_workouts_page()
    {
        $this->logInUser();

        $this->visit('/workouts')
            ->see('add a new workout')
            ->see('pushup');

        $this->assertEquals(Response::HTTP_OK, $this->apiCall('GET', '/')->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_display_the_exercise_units_page()
    {
        $this->logInUser();

        $this->visit('/exercise-units')
            ->see('reps');

        $this->assertEquals(Response::HTTP_OK, $this->apiCall('GET', '/')->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_display_the_exercise_tags_page()
    {
        $this->logInUser();

        $this->visit('/exercise-tags')
            ->see('add a new tag')
            ->see('pushups');

        $this->assertEquals(Response::HTTP_OK, $this->apiCall('GET', '/')->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_display_the_journal_page()
    {
        $this->logInUser();

        $this->visit('/journal')
            ->see('search entries')
            ->see('today')
            ->see('save entry');

        $this->assertEquals(Response::HTTP_OK, $this->apiCall('GET', '/')->getStatusCode());
    }

}
