<?php

use App\Models\Journal\Journal;
use Carbon\Carbon;
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
        $this->assertEquals(Carbon::today()->format('d/m/y'), $content['date']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

    }

    /**
     * @test
     */
    public function it_can_filter_journal_entries()
    {
        $this->logInUser();

        $typing = 'aut';

        $response = $this->call('GET', '/api/journal?typing=' . $typing);
        $content = json_decode($response->getContent(), true)['data'];
//        dd($content);

        $this->assertArrayHasKey('id', $content[0]);
        $this->assertArrayHasKey('date', $content[0]);
        $this->assertArrayHasKey('text', $content[0]);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

    }

    /**
     * @test
     * @return void
     */
    public function it_can_create_a_new_journal_entry()
    {
        $this->logInUser();

        $entry = [
            'date' => '2015-01-01',
            'text' => 'koala'
        ];

        $response = $this->call('POST', '/api/journal', $entry);
        $content = json_decode($response->getContent(), true)['data'];

        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('date', $content);
        $this->assertArrayHasKey('text', $content);

        $this->assertEquals('01/01/15', $content['date']);
        $this->assertEquals('koala', $content['text']);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_update_a_journal_entry()
    {
        $this->logInUser();

        $entry = Journal::forCurrentUser()->first();

        $response = $this->call('PUT', '/api/journal/'.$entry->id, [
            'text' => 'numbat'
        ]);
        $content = json_decode($response->getContent(), true)['data'];

        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('date', $content);
        $this->assertArrayHasKey('text', $content);

        $this->assertEquals('numbat', $content['text']);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
