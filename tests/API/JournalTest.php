<?php

use App\Models\Journal\Journal;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

/**
 * Class JournalTest
 */
class JournalTest extends TestCase {

    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_can_show_a_journal_entry()
    {
        $this->logInUser();

        $entry = Journal::forCurrentUser()->first();

        $response = $this->call('GET', '/api/journal/' . $entry->id);
        $content = json_decode($response->getContent(), true)['data'];

        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('date', $content);
        $this->assertArrayHasKey('text', $content);

        $this->assertEquals(1, $content['id']);
        $this->assertEquals('23/10/15', $content['date']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

    }

    /**
     * @test
     * @return void
     */
//    public function it_can_create_a_new_entry()
//    {
//        $this->logInUser();
//
//        $exercise = [
//            'name' => 'kangaroo',
//            'description' => 'koala'
//        ];
//
//        $response = $this->call('POST', '/api/exercises', $exercise);
//        $content = json_decode($response->getContent(), true);
//
//        $this->assertArrayHasKey('id', $content);
//        $this->assertArrayHasKey('name', $content);
////        $this->assertArrayHasKey('step_number', $content);
//        $this->assertArrayHasKey('description', $content);
//
//        $this->assertEquals('kangaroo', $content['name']);
//        $this->assertEquals('koala', $content['description']);
//
//        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
//    }

    /**
     * @test
     * @return void
     */
//    public function it_can_update_an_entry()
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
}
