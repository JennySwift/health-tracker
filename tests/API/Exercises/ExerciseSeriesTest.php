<?php

use App\Models\Exercises\Exercise;
use App\Models\Exercises\Series;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Todo
 * Class ExerciseSeriesTest
 */
class ExerciseSeriesTest extends TestCase {

    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_can_display_the_history_for_a_series()
    {
        $this->logInUser();

        $response = $this->apiCall('GET', '/api/seriesEntries/1');
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->assertArrayHasKey('exercise', $content[0]);
        $this->assertArrayHasKey('id', $content[0]['exercise']);
        $this->assertArrayHasKey('name', $content[0]['exercise']);
        $this->assertArrayHasKey('description', $content[0]['exercise']);
        $this->assertArrayHasKey('step_number', $content[0]['exercise']);
        $this->assertArrayHasKey('default_unit_id', $content[0]['exercise']);

        $this->assertArrayHasKey('unit', $content[0]);
        $this->assertArrayHasKey('id', $content[0]['unit']);
        $this->assertArrayHasKey('name', $content[0]['unit']);

        $this->assertArrayHasKey('sets', $content[0]);
        $this->assertArrayHasKey('total', $content[0]);
        $this->assertArrayHasKey('quantity', $content[0]);
        $this->assertArrayHasKey('date', $content[0]);
        $this->assertArrayHasKey('days_ago', $content[0]);

        $this->assertEquals(1, $content[0]['exercise']['id']);
        $this->assertEquals('kneeling pushups', $content[0]['exercise']['name']);
        $this->assertEquals('1.00', $content[0]['exercise']['step_number']);
        $this->assertEquals(1, $content[0]['exercise']['default_unit_id']);

        $this->assertEquals(1, $content[0]['unit']['id']);
        $this->assertEquals('reps', $content[0]['unit']['name']);

        $this->assertEquals(2, $content[0]['sets']);
        $this->assertEquals(10, $content[0]['total']);
        $this->assertEquals(5, $content[0]['quantity']);
        $this->assertEquals(0, $content[0]['days_ago']);
        $this->assertEquals(1, $content[2]['days_ago']);
        $this->assertEquals(Carbon::today()->format('d/m/y'), $content[0]['date']);
    }

    /**
     * @test
     */
    public function it_can_show_the_exercises_in_a_series()
    {
        $this->logInUser();

        $response = $this->apiCall('GET', '/api/exerciseSeries/1');
        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('exercises', $content);
//        dd($content);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function it_can_list_the_series()
    {
        $this->logInUser();

        $response = $this->call('GET', '/api/exerciseSeries');
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->assertArrayHasKey('id', $content[0]);
        $this->assertArrayHasKey('name', $content[0]);
        $this->assertArrayHasKey('priority', $content[0]);
        $this->assertArrayHasKey('workout_ids', $content[0]);
        $this->assertArrayHasKey('workouts', $content[0]);
        $this->assertArrayHasKey('lastDone', $content[0]);

        $this->assertEquals(2, $content[0]['id']);
        $this->assertEquals('pullup', $content[0]['name']);

        $this->assertEquals(1, $content[0]['workouts']['data'][0]['id']);
        $this->assertEquals('day one', $content[0]['workouts']['data'][0]['name']);

        $this->assertEquals([
            1
        ], $content[0]['workout_ids']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

    }

    /**
     * @test
     */
    public function it_can_show_a_series()
    {
        $this->logInUser();

        $series = Series::forCurrentUser()->first();

        $response = $this->call('GET', '/api/exerciseSeries/' . $series->id);
        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('name', $content);
        $this->assertArrayHasKey('workout_ids', $content);

        $this->assertEquals(1, $content['id']);
        $this->assertEquals('pushup', $content['name']);
        $this->assertEquals([
            1
        ], $content['workout_ids']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

    }

    /**
     * @test
     * @return void
     */
    public function it_can_update_a_series()
    {
        $this->logInUser();

        $series = Series::forCurrentUser()->first();

        $response = $this->call('PUT', '/api/exerciseSeries/'.$series->id, [
            'name' => 'numbat',
            'priority' => 8
        ]);
//        dd($response);
        $content = json_decode($response->getContent(), true)['data'];
//        dd($content);

        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('name', $content);
        $this->assertArrayHasKey('priority', $content);
        $this->assertArrayHasKey('workout_ids', $content);

        $this->assertEquals(1, $content['id']);
        $this->assertEquals('numbat', $content['name']);
        $this->assertEquals('8', $content['priority']);
        $this->assertEquals([1], $content['workout_ids']);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_update_a_series_including_its_tags()
    {
        $this->logInUser();

        $series = Series::forCurrentUser()->first();

        $response = $this->call('PUT', '/api/exerciseSeries/'.$series->id, [
            'name' => 'numbat',
            'workout_ids' => [2,3]
        ]);
//        dd($response);
        $content = json_decode($response->getContent(), true)['data'];
//        dd($content);

        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('name', $content);
        $this->assertArrayHasKey('workout_ids', $content);

        $this->assertEquals(1, $content['id']);
        $this->assertEquals('numbat', $content['name']);
        $this->assertEquals([2,3], $content['workout_ids']);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_lists_a_series()
    {
//        $this->logInUser();
//
//        $response = $this->apiCall('GET', '/api/exercises');
//        $content = json_decode($response->getContent(), true);
//
//        $this->assertArrayHasKey('id', $content[0]);
//        $this->assertArrayHasKey('name', $content[0]);
//        $this->assertArrayHasKey('step_number', $content[0]);
//        $this->assertArrayHasKey('description', $content[0]);
//
//        $this->assertEquals('kneeling pushups', $content[0]['name']);
//        $this->assertEquals('http://localhost/api/exercises/1', $content[0]['path']);
//        $this->assertEquals('5.00', $content[0]['default_quantity']);
//
//        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_add_a_new_series()
    {
        $this->logInUser();

        $series = [
            'name' => 'kangaroo'
        ];

        $response = $this->call('POST', '/api/exerciseSeries', $series);
        $content = json_decode($response->getContent(), true)['data'];
//        dd($content);

        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('name', $content);
        $this->assertArrayHasKey('workout_ids', $content);

        $this->assertEquals('kangaroo', $content['name']);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_delete_a_series()
    {
        $this->logInUser();

        $series = new Series([
            'name' => 'echidna'
        ]);

        $series->user()->associate($this->user);
        $series->save();
        $series->workouts()->sync([1,2]);

        $this->seeInDatabase('exercise_series', ['name' => 'echidna']);
        $this->seeInDatabase('series_workout', ['series_id' => $series->id, 'workout_id' => 1]);
        $this->seeInDatabase('series_workout', ['series_id' => $series->id, 'workout_id' => 2]);

        $response = $this->call('DELETE', '/api/exerciseSeries/'.$series->id);
        $this->assertEquals(204, $response->getStatusCode());
        $this->missingFromDatabase('exercise_series', ['name' => 'echidna']);

        //Check the rows were deleted in the series_workout pivot table
        $this->missingFromDatabase('series_workout', ['series_id' => $series->id, 'workout_id' => 1]);
        $this->missingFromDatabase('series_workout', ['series_id' => $series->id, 'workout_id' => 2]);

        $response = $this->call('DELETE', '/api/exerciseSeries/'.$series->id);
        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_throws_an_exception_if_user_tries_to_delete_a_series_that_is_in_use()
    {
        $this->logInUser();

        $response = $this->call('DELETE', '/api/exerciseSeries/1');
        $content = json_decode($response->getContent(), true);
//        dd($content);
        $this->assertEquals(400, $response->getStatusCode());
        //Check the series is still in the database
        $this->seeInDatabase('exercise_series', ['name' => 'pushup']);

        $this->assertArrayHasKey('error', $content);
        $this->assertEquals('Series could not be deleted. It is in use.', $content['error']);
    }
}
