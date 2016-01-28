<?php

use App\Models\Tags\Tag;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

class ExerciseTagsTest extends TestCase {

    use DatabaseTransactions;

    /**
     * It lists the exercise tags for the user
     * @test
     * @return void
     */
    public function it_lists_all_exercise_tags()
    {
        $this->logInUser();

        $response = $this->apiCall('GET', '/api/exerciseTags');
        $content = json_decode($response->getContent(), true);

        $this->checkTagKeysExist($content[0]);

        $this->assertEquals('pullups', $content[0]['name']);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_add_a_new_exercise_tag()
    {
        $this->logInUser();

        $tag = [
            'name' => 'kangaroo',
            'for' => 'exercise'
        ];

        $response = $this->call('POST', '/api/exerciseTags', $tag);
        $content = json_decode($response->getContent(), true)['data'];

        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('name', $content);

        $this->assertEquals('kangaroo', $content['name']);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
//    public function it_can_update_an_exercise_tag()
//    {
//        $this->logInUser();
//
//        $unit = Unit::forCurrentUser()->where('for', 'exercise')->first();
//
//        $response = $this->call('PUT', '/api/exerciseUnits/'.$unit->id, [
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
    public function it_can_delete_an_exercise_tag()
    {
        $this->logInUser();

        $tag = new Tag([
            'name' => 'echidna',
            'for' => 'exercise'
        ]);
        $tag->user()->associate($this->user);
        $tag->save();

        $this->seeInDatabase('tags', ['name' => 'echidna']);

        $response = $this->call('DELETE', '/api/exerciseTags/'.$tag->id);
        $this->assertEquals(204, $response->getStatusCode());
        $this->missingFromDatabase('tags', ['name' => 'echidna']);

//        // @TODO Test the 404 for the other methods as well (show, update)
        $response = $this->call('DELETE', '/api/exerciseTags/'.$tag->id);
        $this->assertEquals(404, $response->getStatusCode());
    }
}
