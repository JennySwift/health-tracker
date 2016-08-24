<?php

use App\Models\Units\Unit;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

class ExerciseUnitsTest extends TestCase {

    use DatabaseTransactions;

    /**
     * It lists the exercise units for the user
     * Todo: check only the units for the current user are displayed,
     * without adding the user_id field to the response
     * @test
     * @return void
     */
    public function it_lists_all_exercise_units()
    {
        $this->logInUser();

        $response = $this->apiCall('GET', '/api/exerciseUnits');
        $content = json_decode($response->getContent(), true);

        $this->checkExerciseUnitKeysExist($content[0]);

        $this->assertEquals(2, $content[0]['id']);
        $this->assertEquals('minutes', $content[0]['name']);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_add_a_new_unit()
    {
        $this->logInUser();

        $unit = [
            'name' => 'kangaroo'
        ];

        $response = $this->call('POST', '/api/exerciseUnits', $unit);
        $content = json_decode($response->getContent(), true)['data'];

        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('name', $content);
        $this->assertArrayHasKey('for', $content);

        $this->assertContains($unit['name'], $content);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_update_a_unit()
    {
        $this->logInUser();

        $unit = Unit::forCurrentUser()->where('for', 'exercise')->first();

        $response = $this->call('PUT', '/api/exerciseUnits/'.$unit->id, [
            'name' => 'numbat'
        ]);
        $content = json_decode($response->getContent(), true)['data'];

        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('name', $content);
        $this->assertArrayHasKey('for', $content);

        $this->assertEquals('numbat', $content['name']);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_delete_an_exercise_unit()
    {
        $this->logInUser();

        $unit = new Unit([
            'name' => 'echidna',
            'for' => 'exercise'
        ]);
        $unit->user()->associate($this->user);
        $unit->save();

        $this->seeInDatabase('units', ['name' => 'echidna']);

        $response = $this->call('DELETE', '/api/exerciseUnits/' .$unit->id);

        $this->assertEquals(204, $response->getStatusCode());
        $this->missingFromDatabase('units', ['name' => 'echidna']);

        // @TODO Test the 404 for the other methods as well (show, update)
        $response = $this->call('DELETE', '/api/units/' . $unit->id);
        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_cannot_delete_an_exercise_unit_that_is_in_use()
    {
        DB::beginTransaction();
        $this->logInUser();

        $unit = Unit::where('for', 'exercise')->first();

        $response = $this->call('DELETE', '/api/exerciseUnits/'.$unit->id);
        $content = json_decode($response->getContent(), true);
//        dd($content);

        $this->assertArrayHasKey('error', $content);

        $this->assertEquals('Unit could not be deleted. It is in use.', $content['error']);
        $this->assertEquals(400, $response->getStatusCode());

        DB::rollBack();
    }
}
