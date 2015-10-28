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

        $this->assertArrayHasKey('id', $content[0]);
        $this->assertArrayHasKey('name', $content[0]);
        $this->assertArrayHasKey('stepNumber', $content[0]);
        $this->assertArrayHasKey('series', $content[0]);
        $this->assertArrayHasKey('defaultUnit', $content[0]);
        $this->assertArrayHasKey('defaultQuantity', $content[0]);
        $this->assertArrayHasKey('tag_ids', $content[0]);

        $this->assertEquals(1, $content[0]['id']);
        $this->assertEquals('kneeling pushups', $content[0]['name']);
        $this->assertEquals(1, $content[0]['stepNumber']);
        $this->assertEquals(5, $content[0]['defaultQuantity']);

        $this->assertEquals([
            'id' => 1,
            'name' => 'pushup'
        ], $content[0]['series']);

        $this->assertEquals([
            'id' => 1,
            'name' => 'reps'
        ], $content[0]['defaultUnit']);

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
            'description' => 'koala'
        ];

        $response = $this->call('POST', '/api/exercises', $exercise);
        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('name', $content);
//        $this->assertArrayHasKey('step_number', $content);
        $this->assertArrayHasKey('description', $content);

        $this->assertEquals('kangaroo', $content['name']);
        $this->assertEquals('koala', $content['description']);

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

        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('name', $content);
        $this->assertArrayHasKey('stepNumber', $content);
        $this->assertArrayHasKey('series', $content);
        $this->assertArrayHasKey('defaultUnit', $content);
        $this->assertArrayHasKey('defaultQuantity', $content);
        $this->assertArrayHasKey('tag_ids', $content);

        $this->assertEquals(1, $content['id']);
        $this->assertEquals('kneeling pushups', $content['name']);
        $this->assertEquals(1, $content['stepNumber']);
        $this->assertEquals(5, $content['defaultQuantity']);

        $this->assertEquals([
            'id' => 1,
            'name' => 'pushup'
        ], $content['series']);

        $this->assertEquals([
            'id' => 1,
            'name' => 'reps'
        ], $content['defaultUnit']);

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
            'default_unit_id' => 2
        ]);
        $content = json_decode($response->getContent(), true)['data'];
//        dd($content);

        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('name', $content);
        $this->assertArrayHasKey('description', $content);
        $this->assertArrayHasKey('stepNumber', $content);
        $this->assertArrayHasKey('series', $content);
        $this->assertArrayHasKey('defaultUnit', $content);
        $this->assertArrayHasKey('defaultQuantity', $content);
        $this->assertArrayHasKey('tags', $content);
        $this->assertArrayHasKey('tag_ids', $content);

        $this->assertEquals(1, $content['id']);
        $this->assertEquals('numbat', $content['name']);
        $this->assertEquals('frog', $content['description']);
        $this->assertEquals(2, $content['stepNumber']);
        $this->assertEquals(6, $content['defaultQuantity']);

        $this->assertEquals([
            'id' => 2,
            'name' => 'pullup'
        ], $content['series']);

        $this->assertEquals([
            'id' => 2,
            'name' => 'minutes'
        ], $content['defaultUnit']);

        //Todo: check tags

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

        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('name', $content);
        $this->assertArrayHasKey('stepNumber', $content);
        $this->assertArrayHasKey('series', $content);
        $this->assertArrayHasKey('defaultUnit', $content);
        $this->assertArrayHasKey('defaultQuantity', $content);
        $this->assertArrayHasKey('tags', $content);
        $this->assertArrayHasKey('tag_ids', $content);

        $this->assertEquals(1, $content['id']);
        $this->assertEquals('kneeling pushups', $content['name']);
        $this->assertEquals(1, $content['stepNumber']);
        $this->assertEquals(7, $content['defaultQuantity']);

        $this->assertEquals([
            'id' => 1,
            'name' => 'pushup'
        ], $content['series']);

        $this->assertEquals([
            'id' => 1,
            'name' => 'reps'
        ], $content['defaultUnit']);

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
