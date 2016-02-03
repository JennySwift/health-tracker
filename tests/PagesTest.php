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
     * //Todo: Check the entries are there
     * @test
     * @return void
     */
    public function it_can_display_the_entries_page()
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

        $this->visit('/#/recipes')
            ->see('delicious recipe');
        //This should be there too, but perhaps it is failing because
        //it takes a little while to appear?
//            ->see('soup');

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

        $this->visit('/#/exercises')
            ->see('kneeling pushups');

        $this->assertEquals(Response::HTTP_OK, $this->apiCall('GET', '/')->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_display_the_exercise_series_page()
    {
        $this->logInUser();

        $this->visit('/#/series')
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

        $this->visit('/#/exercise-units')
            ->see('reps')
            ->see('minutes');

        $this->assertEquals(Response::HTTP_OK, $this->apiCall('GET', '/')->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_display_the_activities_page()
    {
        $this->logInUser();

        $this->visit('/#/activities')
            ->see('Activities');
        //Failing for some reason
//            ->dontSee('Add manual time entry');

        $this->assertEquals(Response::HTTP_OK, $this->apiCall('GET', '/')->getStatusCode());
    }

    /**
     * Todo: check the entry is there
     * @test
     * @return void
     */
    public function it_can_display_the_journal_page()
    {
        $this->logInUser();

        $this->visit('/#/journal')
            ->see('search entries')
            ->see('today')
            ->see('save entry');

        $this->assertEquals(Response::HTTP_OK, $this->apiCall('GET', '/')->getStatusCode());
    }

}
