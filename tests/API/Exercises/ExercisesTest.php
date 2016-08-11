<?php

use App\Models\Exercises\Exercise;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class ExercisesTest
 */
class ExercisesTest extends TestCase {

    use DatabaseTransactions;

    /**
     * Todo: finish
     * It lists the exercises for the user
     * @test
     * @return void
     */
    public function it_lists_all_exercises()
    {
        $this->logInUser();

        $response = $this->apiCall('GET', '/api/exercises');
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->checkExerciseKeysExist($content[0]);

        $this->assertEquals(1, $content[0]['id']);
        $this->assertEquals('kneeling pushups', $content[0]['name']);
        $this->assertEquals(1, $content[0]['stepNumber']);
        $this->assertEquals(20, $content[0]['defaultQuantity']);

        $this->assertEquals([
            'id' => 1,
            'name' => 'pushup'
        ], $content[0]['series']);

        $this->assertEquals(1, $content[0]['defaultUnit']['data']['id']);
        $this->assertEquals('reps', $content[0]['defaultUnit']['data']['name']);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_autocomplete_the_exercises()
    {
        $this->logInUser();

        $response = $this->apiCall('GET', '/api/exercises?typing=p');
        $content = json_decode($response->getContent(), true)['data'];
//        dd($content);

        $this->checkExerciseKeysExist($content[0]);

        $this->assertEquals(1, $content[0]['defaultUnit']['data']['id']);

        foreach ($content as $exercise) {
            $this->assertContains('p', $exercise['name']);
        }

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Todo: finish
     * @test
     * @return void
     */
    public function it_can_add_a_new_exercise()
    {
        $this->logInUser();

        $exercise = [
            'name' => 'kangaroo',
            'description' => 'koala',
            'priority' => 2,
            'program_id' => 2,
            'series_id' => 2,
            'step_number' => 2,
            'default_quantity' => 2,
            'default_unit_id' => 2,
            'target' => '2 reps',
            'stretch' => 1
        ];

        $response = $this->call('POST', '/api/exercises', $exercise);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('name', $content);
//        $this->assertArrayHasKey('step_number', $content);
        $this->assertArrayHasKey('description', $content);

        $this->assertEquals('kangaroo', $content['name']);
        $this->assertEquals('koala', $content['description']);
        $this->assertEquals(2, $content['priority']);
        $this->assertEquals(1, $content['stretch']);
        $this->assertEquals(2, $content['program']['id']);
        $this->assertEquals(2, $content['series']['id']);
        $this->assertEquals(2, $content['stepNumber']);
        $this->assertEquals(2, $content['defaultQuantity']);
        $this->assertEquals(2, $content['defaultUnit']['data']['id']);
        $this->assertEquals('2 reps', $content['target']);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function it_can_show_an_exercise()
    {
        $this->logInUser();

        $exercise = Exercise::forCurrentUser()->first();

        $response = $this->call('GET', '/api/exercises/' . $exercise->id);
        $content = json_decode($response->getContent(), true);

        $this->checkExerciseKeysExist($content);

        $this->assertEquals(1, $content['id']);
        $this->assertEquals('kneeling pushups', $content['name']);
        $this->assertEquals(1, $content['stepNumber']);
        $this->assertEquals(20, $content['defaultQuantity']);

        $this->assertEquals([
            'id' => 1,
            'name' => 'pushup'
        ], $content['series']);

        $this->assertEquals(1, $content['defaultUnit']['data']['id']);
        $this->assertEquals('reps', $content['defaultUnit']['data']['name']);

        //Todo: make seeder static for this
//        $this->assertEquals('kneeling pushups', $content['tag_ids']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

    }

    /**
     * @test
     * @return void
     */
    public function it_can_update_an_exercise()
    {
        $this->logInUser();

        $exercise = Exercise::forCurrentUser()->first();

        $response = $this->call('PUT', '/api/exercises/'.$exercise->id, [
            'name' => 'numbat',
            'step_number' => 2,
            'default_quantity' => 6,
            'description' => 'frog',
            'series_id' => 2,
            'default_unit_id' => 2,
            'program_id' => 2,
            'target' => 'something else',
            'priority' => 9,
            'stretch' => 1
        ]);
//        dd($response);
        $content = json_decode($response->getContent(), true)['data'];
//        dd($content);

        $this->checkExerciseKeysExist($content);

        $this->assertEquals(1, $content['id']);
        $this->assertEquals('numbat', $content['name']);
        $this->assertEquals(1, $content['stretch']);
        $this->assertEquals(2, $content['program']['id']);
        $this->assertEquals('frog', $content['description']);
        $this->assertEquals(2, $content['stepNumber']);
        $this->assertEquals(6, $content['defaultQuantity']);
        $this->assertEquals('something else', $content['target']);
        $this->assertEquals(9, $content['priority']);

        $this->assertEquals([
            'id' => 2,
            'name' => 'pullup'
        ], $content['series']);

        $this->assertEquals(2, $content['defaultUnit']['data']['id']);
        $this->assertEquals('minutes', $content['defaultUnit']['data']['name']);

        //Todo: check tags

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_set_stretch_to_false_for_an_exercise()
    {
        $this->logInUser();

        $exercise = Exercise::forCurrentUser()->where('stretch', 1)->first();

        $response = $this->call('PUT', '/api/exercises/'.$exercise->id, [
            'stretch' => 0
        ]);
//        dd($response);
        $content = json_decode($response->getContent(), true)['data'];
//        dd($content);

        $this->checkExerciseKeysExist($content);

        $this->assertEquals(0, $content['stretch']);

        $this->assertEquals(200, $response->getStatusCode());
    }


    /**
     * @test
     * @return void
     */
    public function it_can_update_an_exercise_default_quantity()
    {
        $this->logInUser();

        $exercise = Exercise::forCurrentUser()->first();

        $response = $this->call('PUT', '/api/exercises/'.$exercise->id, [
            'default_quantity' => 7,
        ]);
        $content = json_decode($response->getContent(), true)['data'];
//        dd($content);

        $this->checkExerciseKeysExist($content);

        $this->assertEquals(1, $content['id']);
        $this->assertEquals('kneeling pushups', $content['name']);
        $this->assertEquals(1, $content['stepNumber']);
        $this->assertEquals(7, $content['defaultQuantity']);

        $this->assertEquals([
            'id' => 1,
            'name' => 'pushup'
        ], $content['series']);

        $this->assertEquals(1, $content['defaultUnit']['data']['id']);
        $this->assertEquals('reps', $content['defaultUnit']['data']['name']);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_delete_an_exercise()
    {
        $this->logInUser();

        $exercise = new Exercise([
            'name' => 'echidna'
        ]);

        $exercise->user()->associate($this->user);
        $exercise->save();

        $this->seeInDatabase('exercises', ['name' => 'echidna']);

        $response = $this->call('DELETE', '/api/exercises/'.$exercise->id);
        $this->assertEquals(204, $response->getStatusCode());
        $this->missingFromDatabase('exercises', ['name' => 'echidna']);

        $response = $this->call('DELETE', '/api/units/0');
        $this->assertEquals(404, $response->getStatusCode());
    }
}
