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
        $this->assertArrayHasKey('step_number', $content[0]);
        $this->assertArrayHasKey('description', $content[0]);

        $this->assertEquals('kneeling pushups', $content[0]['name']);
        $this->assertEquals('http://localhost/api/exercises/1', $content[0]['path']);
        $this->assertEquals('5.00', $content[0]['default_quantity']);

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
     * @return void
     */
//    public function it_can_update_an_exercise()
//    {
//        $this->logInUser();
//
//        $unit = Unit::forCurrentUser()->where('for', 'food')->first();
//
//        $response = $this->call('PUT', '/api/foodUnits/'.$unit->id, [
//            'name' => 'numbat'
//        ]);
//        $content = json_decode($response->getContent(), true)['data'];
//
//        $this->assertArrayHasKey('id', $content);
//        $this->assertArrayHasKey('name', $content);
//        $this->assertArrayHasKey('for', $content);
//
//        $this->assertEquals('numbat', $content['name']);
//
//        $this->assertEquals(200, $response->getStatusCode());
//    }

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
